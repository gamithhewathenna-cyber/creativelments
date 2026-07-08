<?php
$adminTitle = 'Enquiries';
require_once 'admin-header.php';

// View single
if (isset($_GET['id'])) {
    $enq = $db->prepare("SELECT * FROM enquiries WHERE id=?")->execute([$_GET['id']]) ? null : null;
    $stmt = $db->prepare("SELECT * FROM enquiries WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $enq = $stmt->fetch();
    if ($enq && $enq['status'] === 'new') {
        $db->prepare("UPDATE enquiries SET status='read' WHERE id=?")->execute([$enq['id']]);
    }
    if (isset($_POST['mark_replied'])) {
        $db->prepare("UPDATE enquiries SET status='replied' WHERE id=?")->execute([$enq['id']]);
        header('Location: /admin/enquiries.php');
        exit;
    }
    if ($enq): ?>
    <a href="/admin/enquiries.php" class="btn btn-outline btn-sm" style="margin-bottom:1.5rem">← Back</a>
    <div class="card">
      <div class="card-header">
        <h2>Enquiry from <?= sanitize($enq['name']) ?></h2>
        <span class="pill <?= $enq['status']==='replied'?'pill-green':($enq['status']==='new'?'pill-red':'pill-yellow') ?>">
          <?= ucfirst($enq['status']) ?>
        </span>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div><strong>Name:</strong><br><?= sanitize($enq['name']) ?></div>
          <div><strong>Email:</strong><br><a href="mailto:<?= sanitize($enq['email']) ?>"><?= sanitize($enq['email']) ?></a></div>
        </div>
        <div class="form-row" style="margin-top:1rem">
          <div><strong>Phone:</strong><br><?= sanitize($enq['phone'] ?: '—') ?></div>
          <div><strong>Service:</strong><br><?= sanitize($enq['service'] ?: '—') ?></div>
        </div>
        <div style="margin-top:1.25rem">
          <strong>Message:</strong>
          <p style="margin-top:.5rem;background:#F8FAFC;padding:1rem;border-radius:8px;font-size:.92rem;line-height:1.7"><?= nl2br(sanitize($enq['message'])) ?></p>
        </div>
        <div style="margin-top:1.5rem;display:flex;gap:1rem">
          <a href="mailto:<?= sanitize($enq['email']) ?>" class="btn btn-primary">Reply via Email</a>
          <form method="POST" style="display:inline">
            <button name="mark_replied" class="btn btn-outline">Mark as Replied</button>
          </form>
        </div>
      </div>
    </div>
    <?php else: ?>
    <p>Enquiry not found.</p>
    <?php endif;
} else {
    // Delete
    if (isset($_GET['delete'])) {
        $db->prepare("DELETE FROM enquiries WHERE id=?")->execute([$_GET['delete']]);
        header('Location: /admin/enquiries.php?deleted=1');
        exit;
    }
    $enquiries = $db->query("SELECT * FROM enquiries ORDER BY created_at DESC")->fetchAll();
    if (isset($_GET['deleted'])): ?><div class="alert alert-success">Enquiry deleted.</div><?php endif; ?>
    <div class="card">
      <div class="card-header"><h2>All Enquiries</h2></div>
      <table>
        <thead><tr><th>Name</th><th>Email</th><th>Service</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($enquiries as $e): ?>
        <tr>
          <td><strong><?= sanitize($e['name']) ?></strong></td>
          <td><?= sanitize($e['email']) ?></td>
          <td><?= sanitize($e['service'] ?: '—') ?></td>
          <td><span class="pill <?= $e['status']==='new'?'pill-red':($e['status']==='replied'?'pill-green':'pill-yellow') ?>"><?= ucfirst($e['status']) ?></span></td>
          <td><?= date('d M Y', strtotime($e['created_at'])) ?></td>
          <td style="display:flex;gap:.5rem">
            <a href="?id=<?= $e['id'] ?>" class="btn btn-outline btn-sm">View</a>
            <a href="?delete=<?= $e['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this enquiry?')">Del</a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$enquiries): ?><tr><td colspan="6" style="text-align:center;color:#313131;padding:2rem">No enquiries yet.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
<?php } ?>

<?php require_once 'admin-footer.php'; ?>
