<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <span class="logo-sub">RECRUITMENT SYSTEM</span>
        <div class="logo-text">EXCELL<span style="color: var(--color-recruiter);">MARK</span></div>
    </div>
    
    <div class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">My Workspace</div>
            <a href="dashboard.php" class="nav-item <?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-table-columns"></i> My Dashboard</span>
            </a>
            <a href="pending.php" class="nav-item <?= $currentPage == 'pending.php' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-inbox"></i> Pending Review</span>
                <span class="nav-badge">8</span>
            </a>
            <a href="reviewed.php" class="nav-item <?= $currentPage == 'reviewed.php' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-magnifying-glass"></i> Reviewed</span>
            </a>
            <a href="shortlisted.php" class="nav-item <?= $currentPage == 'shortlisted.php' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-star"></i> Shortlisted</span>
            </a>
            <a href="hired.php" class="nav-item <?= $currentPage == 'hired.php' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-check"></i> Hired</span>
            </a>
            <a href="overdue.php" class="nav-item <?= $currentPage == 'overdue.php' ? 'active' : '' ?>" style="color: var(--status-danger);">
                <span><i class="fa-solid fa-play"></i> Overdue Flagged</span>
                <span class="nav-badge danger">2</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Job Posts</div>
            <a href="my-posts.php" class="nav-item <?= $currentPage == 'my-posts.php' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-file-invoice"></i> Job Post Overview</span>
                <span class="nav-badge">6</span>
            </a>
            <a href="create-job-post.php" class="nav-item <?= $currentPage == 'create-job-post.php' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-plus"></i> Create Job Post</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Communication</div>
            <a href="messages.php" class="nav-item <?= in_array($currentPage, ['messages.php', 'thread.php']) ? 'active' : '' ?>">
                <span><i class="fa-solid fa-comments"></i> Messages</span>
                <div style="width: 6px; height: 6px; background: var(--status-danger); border-radius: 50%;"></div>
            </a>
            <a href="documents.php" class="nav-item <?= $currentPage == 'documents.php' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-paperclip"></i> Documents</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Performance</div>
            <a href="quota.php" class="nav-item <?= $currentPage == 'quota.php' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-bullseye"></i> Quota Progress</span>
            </a>
            <a href="my-funnel.php" class="nav-item <?= $currentPage == 'my-funnel.php' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-filter"></i> Hiring Funnel</span>
            </a>
        </div>
    </div>
    
    <div class="sidebar-footer">
        <div class="avatar" style="background: var(--color-recruiter);">MS</div>
        <div class="user-info">
            <span class="user-name"><?= htmlspecialchars($_SESSION['full_name']) ?></span>
            <span class="user-role">Senior Recruiter</span>
        </div>
        <a href="/excell-mark/logout.php" style="margin-left: auto; color: var(--text-muted);"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
</aside>
