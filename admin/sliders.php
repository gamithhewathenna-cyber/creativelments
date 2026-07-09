<?php
$adminTitle = 'Hero Slider';
require_once 'admin-auth.php';

// Delete
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM hero_slides WHERE id=?")->execute([$_GET['delete']]);
    header('Location: /admin/sliders.php?msg=deleted');
    exit;
}

// Toggle active
if (isset($_GET['toggle'])) {
    $stmt = $db->prepare("SELECT active FROM hero_slides WHERE id=?");
    $stmt->execute([$_GET['toggle']]);
    $cur = $stmt->fetchColumn();
    $db->prepare("UPDATE hero_slides SET active=? WHERE id=?")->execute([!$cur, $_GET['toggle']]);
    header('Location: /admin/sliders.php');
    exit;
}

// Required dimensions/format so every slide displays identically
const SLIDER_WIDTH  = 1920;
const SLIDER_HEIGHT = 600;
const SLIDER_EXT    = 'jpg';

// Separate, portrait-friendly image for mobile screens
const SLIDER_MOBILE_WIDTH  = 1740;
const SLIDER_MOBILE_HEIGHT = 1262;

$formError = '';
$editSlide = null;

// Save (add or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = intval($_POST['id'] ?? 0);
    $badge       = trim($_POST['badge'] ?? '');
    $title       = trim($_POST['title'] ?? '');
    $titleSize   = intval($_POST['title_font_size'] ?? 0);
    $desc        = trim($_POST['description'] ?? '');
    $buttonText  = trim($_POST['button_text'] ?? '');
    $buttonLink  = trim($_POST['button_link'] ?? '');
    $sort        = intval($_POST['sort_order'] ?? 0);
    $image       = '';
    $imageMobile = '';

    // Handle desktop image upload — every slide must match the same size & format
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg'])) {
            $formError = 'Desktop image must be a ' . strtoupper(SLIDER_EXT) . ' file (all slides must use the same format).';
        } else {
            $dimensions = @getimagesize($_FILES['image']['tmp_name']);
            if (!$dimensions) {
                $formError = 'Could not read the uploaded desktop image.';
            } elseif ($dimensions[0] !== SLIDER_WIDTH || $dimensions[1] !== SLIDER_HEIGHT) {
                $formError = "Desktop image must be exactly " . SLIDER_WIDTH . "×" . SLIDER_HEIGHT . "px (yours was {$dimensions[0]}×{$dimensions[1]}px). All slides must be the same dimensions.";
            } else {
                $newName = uniqid('slide_') . '.' . SLIDER_EXT;
                $dest    = '../uploads/hero/' . $newName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $image = $newName;
                } else {
                    $formError = 'Desktop image upload failed. Please try again.';
                }
            }
        }
    } elseif (!$id) {
        $formError = 'Please choose a desktop slide image.';
    }

    // Handle mobile image upload (optional — falls back to the desktop image if not set)
    if (!$formError && !empty($_FILES['image_mobile']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image_mobile']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg'])) {
            $formError = 'Mobile image must be a ' . strtoupper(SLIDER_EXT) . ' file.';
        } else {
            $dimensions = @getimagesize($_FILES['image_mobile']['tmp_name']);
            if (!$dimensions) {
                $formError = 'Could not read the uploaded mobile image.';
            } elseif ($dimensions[0] !== SLIDER_MOBILE_WIDTH || $dimensions[1] !== SLIDER_MOBILE_HEIGHT) {
                $formError = "Mobile image must be exactly " . SLIDER_MOBILE_WIDTH . "×" . SLIDER_MOBILE_HEIGHT . "px (yours was {$dimensions[0]}×{$dimensions[1]}px).";
            } else {
                $newName = uniqid('slide_mobile_') . '.' . SLIDER_EXT;
                $dest    = '../uploads/hero/' . $newName;
                if (move_uploaded_file($_FILES['image_mobile']['tmp_name'], $dest)) {
                    $imageMobile = $newName;
                } else {
                    $formError = 'Mobile image upload failed. Please try again.';
                }
            }
        }
    }

    if (!$formError) {
        if ($id) {
            $sql = "UPDATE hero_slides SET badge=?,title=?,title_font_size=?,description=?,button_text=?,button_link=?,sort_order=?"
                 . ($image ? ",image=?" : "") . ($imageMobile ? ",image_mobile=?" : "") . " WHERE id=?";
            $params = [$badge,$title,$titleSize,$desc,$buttonText,$buttonLink,$sort];
            if ($image) $params[] = $image;
            if ($imageMobile) $params[] = $imageMobile;
            $params[] = $id;
        } else {
            $sql = "INSERT INTO hero_slides (badge,title,title_font_size,description,button_text,button_link,sort_order,image,image_mobile) VALUES (?,?,?,?,?,?,?,?,?)";
            $params = [$badge,$title,$titleSize,$desc,$buttonText,$buttonLink,$sort,$image,$imageMobile];
        }
        try {
            $db->prepare($sql)->execute($params);
            header('Location: /admin/sliders.php?msg=saved');
            exit;
        } catch (PDOException $e) {
            $formError = 'Database error: ' . $e->getMessage();
        }
    }

    // Validation failed — redisplay the form with what was typed
    $editSlide = ['id' => $id, 'badge' => $badge, 'title' => $title, 'title_font_size' => $titleSize, 'description' => $desc, 'button_text' => $buttonText, 'button_link' => $buttonLink, 'sort_order' => $sort];
}

if (isset($_GET['edit']) && !$editSlide) {
    $stmt = $db->prepare("SELECT * FROM hero_slides WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editSlide = $stmt->fetch();
}

$slides = $db->query("SELECT * FROM hero_slides ORDER BY sort_order")->fetchAll();
require_once 'admin-header.php';
if (isset($_GET['msg'])): ?>
<div class="alert alert-success"><?= $_GET['msg'] === 'saved' ? 'Slide saved successfully.' : 'Slide deleted.' ?></div>
<?php endif; ?>
<?php if ($formError): ?>
<div class="alert alert-error"><?= htmlspecialchars($formError) ?></div>
<?php endif; ?>

<!-- Add/Edit Form -->
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $editSlide ? 'Edit Slide' : 'Add New Slide' ?></h2></div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $editSlide['id'] ?? 0 ?>">
      <div class="form-group"><label>Badge Text (small label above the heading)</label><input name="badge" value="<?= sanitize($editSlide['badge'] ?? '') ?>" placeholder="e.g. Trusted by 130+ businesses across Australia & Sri Lanka"></div>
      <div class="form-row">
        <div class="form-group" style="max-width:500px">
          <label>Heading *</label>
          <input name="title" required value="<?= htmlspecialchars($editSlide['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="e.g. Your Digital Agency for Melbourne & Sydney">
          <small style="color:#8892A4;display:block;margin-top:.4rem">Type &lt;br&gt; anywhere to force a line break.</small>
        </div>
        <div class="form-group">
          <label>Heading Font Size (px)</label>
          <input name="title_font_size" type="number" min="16" max="120" value="<?= !empty($editSlide['title_font_size']) ? intval($editSlide['title_font_size']) : '' ?>" placeholder="e.g. 45" style="width:120px">
          <small style="color:#8892A4;display:block;margin-top:.4rem">Leave blank for the default responsive size.</small>
        </div>
      </div>
      <div class="form-group"><label>Description</label><textarea name="description" placeholder="Supporting paragraph text"><?= sanitize($editSlide['description'] ?? '') ?></textarea></div>
      <div class="form-row">
        <div class="form-group"><label>Button Text (optional)</label><input name="button_text" value="<?= sanitize($editSlide['button_text'] ?? '') ?>" placeholder="e.g. Get a Free Quote"></div>
        <div class="form-group"><label>Button Link (optional)</label><input name="button_link" type="url" value="<?= sanitize($editSlide['button_link'] ?? '') ?>" placeholder="https://wa.me/94777130597"></div>
      </div>
      <div class="form-group"><label>Sort Order</label><input name="sort_order" type="number" value="<?= $editSlide['sort_order'] ?? 0 ?>" style="width:100px"></div>
      <div class="form-group">
        <label>Desktop Slide Image <?= $editSlide['id'] ?? 0 ? '' : '*' ?></label>
        <input type="file" name="image" accept=".jpg,.jpeg">
        <small style="color:#8892A4;display:block;margin-top:.4rem">Must be exactly 1920&times;600px, JPG format &mdash; every slide must match so the slider displays consistently.</small>
        <?php if (!empty($editSlide['image'])): ?><br><small>Current: <?= sanitize($editSlide['image']) ?></small><?php endif; ?>
      </div>
      <div class="form-group">
        <label>Mobile Slide Image (optional)</label>
        <input type="file" name="image_mobile" accept=".jpg,.jpeg">
        <small style="color:#8892A4;display:block;margin-top:.4rem">Must be exactly 1740&times;1262px, JPG format. Shown on phones/tablets instead of the desktop image. Leave blank to reuse the desktop image on mobile.</small>
        <?php if (!empty($editSlide['image_mobile'])): ?><br><small>Current: <?= sanitize($editSlide['image_mobile']) ?></small><?php endif; ?>
      </div>
      <button type="submit" class="btn btn-primary">Save Slide</button>
      <?php if ($editSlide): ?><a href="/admin/sliders.php" class="btn btn-outline" style="margin-left:.5rem">Cancel</a><?php endif; ?>
    </form>
  </div>
</div>

<!-- Slides Table -->
<div class="card">
  <div class="card-header"><h2>All Slides (<?= count($slides) ?>)</h2></div>
  <table>
    <thead><tr><th>Image</th><th>Heading</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($slides as $s): ?>
    <tr>
      <td><?php if ($s['image']): ?><img src="/uploads/hero/<?= sanitize($s['image']) ?>" alt="" style="width:80px;height:32px;object-fit:cover;border-radius:4px"><?php else: ?>&mdash;<?php endif; ?></td>
      <td><strong><?= sanitize($s['title']) ?></strong></td>
      <td><span class="pill <?= $s['active'] ? 'pill-green' : 'pill-red' ?>"><?= $s['active'] ? 'Active' : 'Hidden' ?></span></td>
      <td style="display:flex;gap:.5rem">
        <a href="?edit=<?= $s['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
        <a href="?toggle=<?= $s['id'] ?>" class="btn btn-outline btn-sm"><?= $s['active'] ? 'Hide' : 'Show' ?></a>
        <a href="?delete=<?= $s['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Del</a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$slides): ?><tr><td colspan="4" style="text-align:center;color:#313131;padding:2rem">No slides yet. Add your first one above.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'admin-footer.php'; ?>
