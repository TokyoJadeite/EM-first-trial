<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
requireRole('recruiter');

$recruiterId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT a.*, u.full_name as applicant_name, jp.title as job_title FROM applications a JOIN users u ON a.applicant_id = u.id JOIN job_posts jp ON a.job_post_id = jp.id WHERE a.recruiter_id = ? AND a.stage = 'Hired' ORDER BY a.hired_at DESC");
$stmt->execute([$recruiterId]);
$applicants = $stmt->fetchAll();

$pageTitle = "Hired Applicants";
include '../includes/header.php';
include '../includes/nav-recruiter.php';
?>

<div class="main-content">
    <div class="top-header">
        <h1>Hired Applicants</h1>
    </div>

    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Job Applied</th>
                        <th>Hired Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($applicants) === 0): ?>
                        <tr><td colspan="4">No hired applications yet. Keep going!</td></tr>
                    <?php endif; ?>
                    <?php foreach ($applicants as $app): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($app['applicant_name']) ?></strong></td>
                        <td><?= htmlspecialchars($app['job_title']) ?></td>
                        <td><?= date('M d, Y', strtotime($app['hired_at'])) ?></td>
                        <td><span class="badge badge-low">Hired</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
