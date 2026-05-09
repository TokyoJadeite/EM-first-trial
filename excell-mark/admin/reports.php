<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
requireRole('admin');

$pageTitle = "Reports Export";
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
    

    <div class="dashboard-grid">
        <div class="card">
            <div class="card-header">
                <h3>Performance Summary Report</h3>
            </div>
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Generate a comprehensive PDF report containing recruiter metrics, funnel data, and quota progress for the current month.</p>
            <button onclick="window.print()" class="btn btn-admin"><i class="fa-solid fa-file-pdf"></i> Download PDF</button>
            <p style="margin-top: 1rem; font-size: 0.8rem; color: var(--text-muted);">* Note: Uses browser print-to-PDF as a stub for TCPDF.</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Raw Data Export</h3>
            </div>
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Export raw pipeline and application data to an Excel (CSV) format for custom analysis.</p>
            <a href="?export=csv" class="btn btn-outline" style="color: var(--status-low); border-color: var(--status-low);"><i class="fa-solid fa-file-excel"></i> Download Excel / CSV</a>
            <p style="margin-top: 1rem; font-size: 0.8rem; color: var(--text-muted);">* Note: CSV stub implemented.</p>
        </div>
    </div>
</div>

<?php 
// Simple CSV Export stub
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    ob_clean();
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="excellmark_export_' . date('Y-m-d') . '.csv"');
    
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Application ID', 'Applicant', 'Job Post', 'Stage', 'Applied Date']);
    
    $stmt = $pdo->query("SELECT a.id, u.full_name, jp.title, a.stage, a.applied_at FROM applications a JOIN users u ON a.applicant_id = u.id JOIN job_posts jp ON a.job_post_id = jp.id");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        fputcsv($out, $row);
    }
    fclose($out);
    exit;
}
?>

</div>
</div>
<?php include '../includes/footer.php'; ?>
