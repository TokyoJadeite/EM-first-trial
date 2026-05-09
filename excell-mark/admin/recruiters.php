<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
requireRole('admin');

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create') {
        $fullName = $_POST['full_name'];
        $email = $_POST['email'];
        $contact = $_POST['contact_number'];
        $password = password_hash('password', PASSWORD_DEFAULT); // Default password

        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already exists.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role, contact_number) VALUES (?, ?, ?, 'recruiter', ?)");
            $stmt->execute([$fullName, $email, $password, $contact]);
            $success = "Recruiter added successfully with default password 'password'.";
        }
    }
}

// Pagination
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$totalRows = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'recruiter'")->fetchColumn();
$totalPages = ceil($totalRows / $limit);

$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'recruiter' ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$recruiters = $stmt->fetchAll();

$pageTitle = "Recruiter Management";
include '../includes/header.php';
?>

<div class="main-wrapper">
    <?php include '../includes/nav-admin.php'; ?>
    <div class="top-nav">
        <div class="page-title">
            <?= $pageTitle ?? 'Admin' ?>
        </div>
    </div>
    <div class="content-area">
    

    <?php if ($error): ?><div class="flash-message flash-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="flash-message flash-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <div class="dashboard-grid" style="grid-template-columns: 1fr 2fr;">
        <div class="card">
            <div class="card-header">
                <h3>Add New Recruiter</h3>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact_number" class="form-control">
                </div>
                <button type="submit" class="btn btn-admin" style="width: 100%;">Add Recruiter</button>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Active Recruiters</h3>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Joined</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($recruiters) === 0): ?>
                            <tr><td colspan="4">No recruiters found.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($recruiters as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['full_name']) ?></td>
                            <td><?= htmlspecialchars($r['email']) ?></td>
                            <td><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
                            <td><span class="badge badge-low">Active</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($totalPages > 1): ?>
            <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                <?php for($i=1; $i<=$totalPages; $i++): ?>
                    <a href="?p=<?= $i ?>" class="btn <?= $i === $page ? 'btn-admin' : 'btn-outline' ?>" style="padding: 0.2rem 0.6rem;"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</div>
</div>
<?php include '../includes/footer.php'; ?>
