<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div style="height: 60px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; padding: 0 2rem; background: var(--bg-surface); position: sticky; top: 0; z-index: 10;">
    <div class="logo-text" style="font-size: 1.5rem; margin-right: 3rem;">EXCELL<span style="color: var(--color-applicant);">MARK</span></div>
    
    <div style="display: flex; gap: 2rem;">
        <a href="home.php" style="font-size: 0.85rem; font-weight: <?= $currentPage == 'home.php' ? '600' : '400' ?>; color: <?= $currentPage == 'home.php' ? 'var(--color-applicant)' : 'var(--text-secondary)' ?>;">Home</a>
        <a href="jobs.php" style="font-size: 0.85rem; font-weight: <?= $currentPage == 'jobs.php' ? '600' : '400' ?>; color: <?= $currentPage == 'jobs.php' ? 'var(--color-applicant)' : 'var(--text-secondary)' ?>;">Browse Jobs</a>
        <a href="my-application.php" style="font-size: 0.85rem; font-weight: <?= $currentPage == 'my-application.php' ? '600' : '400' ?>; color: <?= $currentPage == 'my-application.php' ? 'var(--color-applicant)' : 'var(--text-secondary)' ?>;">My Application</a>
        <a href="messages.php" style="font-size: 0.85rem; font-weight: <?= $currentPage == 'messages.php' ? '600' : '400' ?>; color: <?= $currentPage == 'messages.php' ? 'var(--color-applicant)' : 'var(--text-secondary)' ?>;">Messages</a>
        <a href="documents.php" style="font-size: 0.85rem; font-weight: <?= $currentPage == 'documents.php' ? '600' : '400' ?>; color: <?= $currentPage == 'documents.php' ? 'var(--color-applicant)' : 'var(--text-secondary)' ?>;">Documents</a>
    </div>
    
    <div style="margin-left: auto; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--bg-surface-light); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; position: relative;">
            <i class="fa-solid fa-bell" style="color: var(--status-warning);"></i>
            <div style="position: absolute; top: -2px; right: -2px; width: 10px; height: 10px; background: var(--status-warning); border-radius: 50%;"></div>
        </div>
        <div class="avatar" style="background: rgba(167, 139, 250, 0.2); color: var(--color-admin); border: 1px solid var(--color-admin);">LC</div>
        <a href="/excell-mark/logout.php" style="margin-left: 0.5rem; color: var(--text-muted);"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
</div>
