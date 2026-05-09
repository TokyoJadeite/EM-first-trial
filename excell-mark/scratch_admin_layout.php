<?php
$files = glob("C:/xampp/htdocs/excell-mark/admin/*.php");
foreach($files as $f) {
    if (basename($f) == 'dashboard.php') continue;
    $content = file_get_contents($f);
    
    // Skip if already has main-wrapper
    if (strpos($content, 'main-wrapper') !== false) continue;
    if (strpos($content, 'main-content') === false) continue;

    // Remove include '../includes/nav-admin.php';
    $content = preg_replace("/include '\.\.\/includes\/nav-admin\.php';\r?\n?/", "", $content);
    
    // Replace <div class="main-content">
    $replacement = "<div class=\"main-wrapper\">\n    <?php include '../includes/nav-admin.php'; ?>\n    <div class=\"top-nav\">\n        <div class=\"page-title\">\n            <?= \$pageTitle ?? 'Admin' ?>\n        </div>\n    </div>\n    <div class=\"content-area\">";
    $content = preg_replace('/<div class="main-content">/', $replacement, $content);
    
    // Remove top-header block
    $content = preg_replace('/<div class="top-header">.*?<\/div>/s', '', $content);
    
    // Close content-area div before footer
    $content = preg_replace("/<\?php include '\.\.\/includes\/footer\.php'; \?>/", "</div>\n</div>\n<?php include '../includes/footer.php'; ?>", $content);
    
    file_put_contents($f, $content);
}
echo "Done";
?>
