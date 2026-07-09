<?php
$adminTitle = 'Dashboard';
require_once 'admin-header.php';

$totalEnquiries  = $db->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();
$newEnq          = $db->query("SELECT COUNT(*) FROM enquiries WHERE status='new'")->fetchColumn();
$totalProjects   = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$totalPosts      = $db->query("SELECT COUNT(*) FROM posts WHERE status='published'")->fetchColumn();
$recentEnquiries = $db->query("SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 8")->fetchAll();
?>

<?php if (($_GET['error'] ?? '') === 'forbidden'): ?>
<div class="alert alert-error">Your account doesn't have permission to access that page.</div>
<?php endif; ?>

<div class="stat-cards">
  <div class="stat-card">
    <div class="num"><?= $newEnq ?></div>
    <div class="label">New Enquiries</div>
  </div>
  <div class="stat-card">
    <div class="num"><?= $totalEnquiries ?></div>
    <div class="label">Total Enquiries</div>
  </div>
  <div class="stat-card">
    <div class="num"><?= $totalProjects ?></div>
    <div class="label">Portfolio Projects</div>
  </div>
  <div class="stat-card">
    <div class="num"><?= $totalPosts ?></div>
    <div class="label">Blog Posts</div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2>Recent Enquiries</h2>
    <a href="/admin/enquiries.php" class="btn btn-outline btn-sm">View All</a>
  </div>
  <table>
    <thead>
      <tr>
        <th>Name</th><th>Email</th><th>Service</th><th>Status</th><th>Date</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($recentEnquiries as $e): ?>
      <tr>
        <td><strong><?= sanitize($e['name']) ?></strong></td>
        <td><?= sanitize($e['email']) ?></td>
        <td><?= sanitize($e['service'] ?: '—') ?></td>
        <td>
          <span class="pill <?= $e['status']==='new'?'pill-red':($e['status']==='replied'?'pill-green':'pill-yellow') ?>">
            <?= ucfirst($e['status']) ?>
          </span>
        </td>
        <td><?= date('d M Y', strtotime($e['created_at'])) ?></td>
        <td><a href="/admin/enquiries.php?id=<?= $e['id'] ?>" class="btn btn-outline btn-sm">View</a></td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$recentEnquiries): ?><tr><td colspan="6" style="text-align:center;color:#313131;padding:2rem">No enquiries yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'admin-footer.php'; ?>
