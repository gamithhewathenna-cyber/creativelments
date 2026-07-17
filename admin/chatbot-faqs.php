<?php
$adminTitle = 'Chatbot FAQs';
require_once 'admin-auth.php';

// Self-installing — so this page works immediately even if install.sql hasn't been re-run.
$db->exec("CREATE TABLE IF NOT EXISTS chatbot_faqs (
  id int(11) NOT NULL AUTO_INCREMENT,
  question varchar(500) NOT NULL,
  answer text NOT NULL,
  keywords varchar(500) DEFAULT '',
  sort_order int(11) DEFAULT 0,
  active tinyint(1) DEFAULT 1,
  created_at timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Delete
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM chatbot_faqs WHERE id=?")->execute([$_GET['delete']]);
    header('Location: /admin/chatbot-faqs.php?msg=deleted');
    exit;
}

// Toggle active
if (isset($_GET['toggle'])) {
    $stmt = $db->prepare("SELECT active FROM chatbot_faqs WHERE id=?");
    $stmt->execute([$_GET['toggle']]);
    $cur = $stmt->fetchColumn();
    $db->prepare("UPDATE chatbot_faqs SET active=? WHERE id=?")->execute([!$cur, $_GET['toggle']]);
    header('Location: /admin/chatbot-faqs.php');
    exit;
}

// Move up / down (swap position, then renumber sort_order sequentially)
if (isset($_GET['move_up']) || isset($_GET['move_down'])) {
    $direction = isset($_GET['move_up']) ? -1 : 1;
    $id = intval($_GET['move_up'] ?? $_GET['move_down']);
    $order = $db->query("SELECT id FROM chatbot_faqs ORDER BY sort_order, id")->fetchAll(PDO::FETCH_COLUMN);
    $pos = array_search($id, $order);
    if ($pos !== false) {
        $swapPos = $pos + $direction;
        if (isset($order[$swapPos])) {
            [$order[$pos], $order[$swapPos]] = [$order[$swapPos], $order[$pos]];
        }
    }
    foreach ($order as $i => $faqId) {
        $db->prepare("UPDATE chatbot_faqs SET sort_order=? WHERE id=?")->execute([$i, $faqId]);
    }
    header('Location: /admin/chatbot-faqs.php');
    exit;
}

$formError   = '';
$importMsg   = '';
$editFaq     = null;

// Bulk import — paste "Q: ...\nA: ..." blocks separated by a blank line
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_import'])) {
    $raw    = trim($_POST['bulk_import']);
    $blocks = preg_split('/\n\s*\n/', $raw);
    $sortNext = (int) $db->query("SELECT COALESCE(MAX(sort_order),-1) FROM chatbot_faqs")->fetchColumn() + 1;
    $imported = 0;
    foreach ($blocks as $block) {
        if (!preg_match('/Q:\s*(.+?)\s*\nA:\s*(.+)/is', trim($block), $m)) continue;
        $q = trim($m[1]);
        $a = trim($m[2]);
        if ($q === '' || $a === '') continue;
        $db->prepare("INSERT INTO chatbot_faqs (question, answer, sort_order) VALUES (?,?,?)")
           ->execute([$q, $a, $sortNext++]);
        $imported++;
    }
    header('Location: /admin/chatbot-faqs.php?msg=imported&count=' . $imported);
    exit;
}

// Save (add or edit single FAQ)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question'])) {
    $id       = intval($_POST['id'] ?? 0);
    $question = trim($_POST['question'] ?? '');
    $answer   = trim($_POST['answer'] ?? '');
    $keywords = trim($_POST['keywords'] ?? '');
    $sort     = intval($_POST['sort_order'] ?? 0);

    if ($question === '' || $answer === '') {
        $formError = 'Question and answer are both required.';
    } else {
        if ($id) {
            $db->prepare("UPDATE chatbot_faqs SET question=?,answer=?,keywords=?,sort_order=? WHERE id=?")
               ->execute([$question, $answer, $keywords, $sort, $id]);
        } else {
            $db->prepare("INSERT INTO chatbot_faqs (question,answer,keywords,sort_order) VALUES (?,?,?,?)")
               ->execute([$question, $answer, $keywords, $sort]);
        }
        header('Location: /admin/chatbot-faqs.php?msg=saved');
        exit;
    }

    $editFaq = ['id' => $id, 'question' => $question, 'answer' => $answer, 'keywords' => $keywords, 'sort_order' => $sort];
}

if (isset($_GET['edit']) && !$editFaq) {
    $stmt = $db->prepare("SELECT * FROM chatbot_faqs WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editFaq = $stmt->fetch();
}

$faqs = $db->query("SELECT * FROM chatbot_faqs ORDER BY sort_order, id")->fetchAll();
require_once 'admin-header.php';
if (isset($_GET['msg'])): ?>
<div class="alert alert-success">
  <?php if ($_GET['msg'] === 'imported'): ?>
    Imported <?= intval($_GET['count'] ?? 0) ?> FAQ<?= intval($_GET['count'] ?? 0) === 1 ? '' : 's' ?> successfully.
  <?php elseif ($_GET['msg'] === 'saved'): ?>
    FAQ saved successfully.
  <?php else: ?>
    FAQ deleted.
  <?php endif; ?>
</div>
<?php endif; ?>
<?php if ($formError): ?>
<div class="alert alert-error"><?= htmlspecialchars($formError) ?></div>
<?php endif; ?>

<!-- Add/Edit Form -->
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $editFaq ? 'Edit FAQ' : 'Add New FAQ' ?></h2></div>
  <div class="card-body">
    <form method="POST">
      <input type="hidden" name="id" value="<?= $editFaq['id'] ?? 0 ?>">
      <div class="form-group"><label>Question *</label><input name="question" required value="<?= sanitize($editFaq['question'] ?? '') ?>" placeholder="e.g. How much does a website cost?"></div>
      <div class="form-group"><label>Answer *</label><textarea name="answer" required placeholder="Write the answer the chatbot should give…"><?= sanitize($editFaq['answer'] ?? '') ?></textarea></div>
      <div class="form-row">
        <div class="form-group">
          <label>Extra Keywords (optional)</label>
          <input name="keywords" value="<?= sanitize($editFaq['keywords'] ?? '') ?>" placeholder="e.g. price, pricing, cost, quote">
          <small style="color:#8892A4;display:block;margin-top:.4rem">Comma-separated words that should also match this FAQ, besides the question text itself.</small>
        </div>
        <div class="form-group"><label>Sort Order</label><input name="sort_order" type="number" value="<?= $editFaq['sort_order'] ?? 0 ?>" style="width:100px"></div>
      </div>
      <button type="submit" class="btn btn-primary">Save FAQ</button>
      <?php if ($editFaq): ?><a href="/admin/chatbot-faqs.php" class="btn btn-outline" style="margin-left:.5rem">Cancel</a><?php endif; ?>
    </form>
  </div>
</div>

<!-- Bulk Import -->
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>Bulk Import from a Document</h2></div>
  <div class="card-body">
    <p style="font-size:.85rem;color:#313131;margin-bottom:1rem">Paste your FAQ document below using this format — one <strong>Q:</strong> / <strong>A:</strong> pair per block, separated by a blank line. Each block becomes one FAQ.</p>
    <form method="POST">
      <div class="form-group">
        <textarea name="bulk_import" style="min-height:220px;font-family:monospace" placeholder="Q: What services do you offer?
A: We offer web design, SEO, branding, and digital marketing.

Q: How much does a website cost?
A: Pricing depends on scope — get in touch for a free, no-obligation quote."></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Import FAQs</button>
    </form>
  </div>
</div>

<!-- FAQ Table -->
<div class="card">
  <div class="card-header"><h2>All FAQs (<?= count($faqs) ?>)</h2></div>
  <table>
    <thead><tr><th>Order</th><th>Question</th><th>Answer</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($faqs as $i => $f): ?>
    <tr>
      <td style="display:flex;gap:.25rem">
        <?php if ($i > 0): ?><a href="?move_up=<?= $f['id'] ?>" class="btn btn-outline btn-sm" title="Move up">&uarr;</a><?php else: ?><span class="btn btn-outline btn-sm" style="visibility:hidden">&uarr;</span><?php endif; ?>
        <?php if ($i < count($faqs) - 1): ?><a href="?move_down=<?= $f['id'] ?>" class="btn btn-outline btn-sm" title="Move down">&darr;</a><?php else: ?><span class="btn btn-outline btn-sm" style="visibility:hidden">&darr;</span><?php endif; ?>
      </td>
      <td><strong><?= sanitize($f['question']) ?></strong></td>
      <td style="max-width:340px"><?= sanitize(mb_strimwidth($f['answer'], 0, 120, '…')) ?></td>
      <td><span class="pill <?= $f['active'] ? 'pill-green' : 'pill-red' ?>"><?= $f['active'] ? 'Active' : 'Hidden' ?></span></td>
      <td style="display:flex;gap:.5rem">
        <a href="?edit=<?= $f['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
        <a href="?toggle=<?= $f['id'] ?>" class="btn btn-outline btn-sm"><?= $f['active'] ? 'Hide' : 'Show' ?></a>
        <a href="?delete=<?= $f['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Del</a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$faqs): ?><tr><td colspan="5" style="text-align:center;color:#313131;padding:2rem">No FAQs yet. Add one above or bulk-import your document.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'admin-footer.php'; ?>
