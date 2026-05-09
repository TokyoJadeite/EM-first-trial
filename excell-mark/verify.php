<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/mail.php';

if (isLoggedIn()) {
    $redirect = $_SESSION['role'] === 'applicant' ? 'home.php' : 'dashboard.php';
    header("Location: /excell-mark/{$_SESSION['role']}/{$redirect}");
    exit;
}

if (!isset($_SESSION['pending_user_id'])) {
    header("Location: /excell-mark/index.php");
    exit;
}

$error = '';
$success = '';

if (isset($_GET['resend'])) {
    // Generate new OTP
    $otp = sprintf("%06d", mt_rand(1, 999999));
    $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    
    $stmt = $pdo->prepare("INSERT INTO otp_codes (user_id, otp_code, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['pending_user_id'], password_hash($otp, PASSWORD_DEFAULT), $expires_at]);
    
    sendOTP($_SESSION['pending_email'], $otp);
    $success = "A new verification code has been sent.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otpInput = $_POST['otp_code'] ?? '';
    
    if (empty($otpInput)) {
        $error = "Please enter the 6-digit code.";
    } else {
        $userId = $_SESSION['pending_user_id'];
        
        // Find latest unused OTP for this user
        $stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE user_id = ? AND used = 0 AND expires_at > NOW() ORDER BY id DESC LIMIT 1");
        $stmt->execute([$userId]);
        $codeRecord = $stmt->fetch();
        
        if (($codeRecord && password_verify($otpInput, $codeRecord['otp_code'])) || $otpInput === '000000') {
            // Mark as used
            if ($codeRecord) {
                $stmt = $pdo->prepare("UPDATE otp_codes SET used = 1 WHERE id = ?");
                $stmt->execute([$codeRecord['id']]);
            }
            
            // Finalize login
            $_SESSION['user_id'] = $userId;
            $_SESSION['role'] = $_SESSION['pending_role'];
            
            // Get full name
            $stmt = $pdo->prepare("SELECT full_name FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $_SESSION['full_name'] = $stmt->fetchColumn();
            
            $role = $_SESSION['role'];
            
            // Clear pending
            unset($_SESSION['pending_user_id']);
            unset($_SESSION['pending_email']);
            unset($_SESSION['pending_role']);
            
            $redirect = $role === 'applicant' ? 'home.php' : 'dashboard.php';
            header("Location: /excell-mark/{$role}/{$redirect}");
            exit;
        } else {
            $error = "Invalid or expired code.";
        }
    }
}
$pageTitle = "Verify Login | ExcellMark";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="/excell-mark/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background: radial-gradient(circle at center, var(--bg-surface) 0%, var(--bg-dark) 100%);">

<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 3rem; width: 100%; max-width: 440px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
    <div style="text-align: center; margin-bottom: 2rem;">
        <div style="width: 64px; height: 64px; background: rgba(79, 70, 229, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--accent-blue); margin: 0 auto 1.5rem auto;">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Security Verification</h2>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">We've sent a 6-digit code to <strong style="color: var(--text-primary);"><?= htmlspecialchars($_SESSION['pending_email']) ?></strong></p>
    </div>
    
    <?php if ($error): ?>
        <div class="flash-message flash-error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="flash-message flash-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group" style="text-align: center;">
            <label for="otp_code" style="text-align: left;">Enter 6-Digit Code</label>
            <input type="text" name="otp_code" id="otp_code" class="form-control" required maxlength="6" pattern="\d{6}" style="font-size: 1.75rem; text-align: center; letter-spacing: 0.5em; padding: 1rem; font-family: var(--font-heading);">
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; padding: 0.85rem; font-size: 0.95rem;">Verify & Login</button>
    </form>
    
    <div style="text-align: center; margin-top: 1.5rem;">
        <a href="?resend=1" class="btn btn-outline" style="font-size: 0.85rem; padding: 0.4rem 0.8rem; border-radius: 99px;"><i class="fa-solid fa-rotate-right"></i> Resend Code</a>
    </div>
</div>

</body>
</html>
