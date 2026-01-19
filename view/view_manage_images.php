<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Manage Images</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="manage-page">

    <header class="header">
        <h1>Manage images</h1>
        <span class="cart">🛒</span>
    </header>

    <main>
        <!-- Back button and title -->
        <div class="page-header">
            <a href="browser/index" class="back-btn">←</a>
            <span class="page-subtitle">Manage Images for "<?= htmlspecialchars($item->title) ?>"</span>
        </div>

        <!-- Add New Images Section -->
        <div class="card">
            <div class="card-title">Add New Images</div>
            <form action="manage_images/upload/<?= $item->id ?>" method="post" enctype="multipart/form-data">
                <label class="form-label">Select Images</label>
                <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.gif,.webp" class="file-input">
                <p class="help-text">You can select multiple images (JPG, PNG, GIF, WebP). Images will be added to the
                    end of your current list.</p>
                <button type="submit" class="upload-btn">Upload Images</button>
            </form>
        </div>

        <!-- Current Images Section -->
        <div class="card">
            <div class="card-title">Current Images</div>

            <?php if (!empty($pictures)): ?>
                <div class="img-grid">
                    <?php foreach ($pictures as $index => $picture): ?>
                        <div class="img-box">
                            <img src="<?= $web_root . $picture->picture_path ?>" alt="Image <?= $picture->priority ?>"
                                class="img-thumb">
                            <div class="img-actions">
                                <!-- Move Left -->
                                <?php if ($picture->priority > 1): ?>
                                    <a href="manage_images/move_left/<?= $item->id ?>/<?= $picture->priority ?>"
                                        class="action-btn">←</a>
                                <?php else: ?>
                                    <span class="action-btn disabled">←</span>
                                <?php endif; ?>

                                <!-- Move Right -->
                                <?php if ($picture->priority < $picture_count): ?>
                                    <a href="manage_images/move_right/<?= $item->id ?>/<?= $picture->priority ?>"
                                        class="action-btn">→</a>
                                <?php else: ?>
                                    <span class="action-btn disabled">→</span>
                                <?php endif; ?>

                                <!-- Delete -->
                                <a href="manage_images/delete/<?= $item->id ?>/<?= $picture->priority ?>"
                                    class="action-btn del">✕</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color: #9ca3af; text-align: center;">No images yet.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="footer">
            <nav class="footer-nav">
                <a href="browser/index" class="nav-item">
                    <span class="icon">🔎</span>
                    <span class="label">Browse</span>
                </a>
                <a href="#" class="nav-item">
                    <span class="icon">🏠</span>
                    <span class="label">My Items</span>
                </a>
                <a href="#" class="nav-item">
                    <span class="icon">➕</span>
                    <span class="label">Add Offer</span>
                </a>
                <a href="#" class="nav-item">
                    <span class="icon">⚙️</span>
                    <span class="label">Profile</span>
                </a>
            </nav>
        </div>
    </footer>

</body>

</html>