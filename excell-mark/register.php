<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/db.php';

if (isLoggedIn()) {
    header("Location: /excell-mark/index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact_number'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($fullName) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role, contact_number) VALUES (?, ?, ?, 'applicant', ?)");
            if ($stmt->execute([$fullName, $email, $hash, $contact])) {
                header("Location: /excell-mark/index.php?registered=1");
                exit;
            } else {
                $error = "An error occurred during registration.";
            }
        }
    }
}
$pageTitle = "Register";
include 'includes/header.php';
?>

<div class="auth-layout">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Applicant Registration</h2>
            <p>Join ExcellMark to find your next opportunity</p>
        </div>
        
        <?php if ($error): ?>
            <div class="flash-message flash-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" name="full_name" id="full_name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" name="password" id="password" class="form-control" required minlength="6">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required minlength="6">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Register</button>
            
            <div style="text-align: center; margin-top: 1.5rem;">
                <p style="color: var(--text-secondary); font-size: 0.9rem;">
                    Already have an account? <a href="index.php" style="color: var(--color-recruiter); font-weight: 500;">Sign In</a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
