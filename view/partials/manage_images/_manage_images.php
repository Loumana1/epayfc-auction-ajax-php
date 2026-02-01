<div class="card">
            <div class="card-title">Add New Images</div>
            <form action="manage_images/upload/<?= $item->get_Id() ?>" method="post" enctype="multipart/form-data">
                <label class="form-label">Select Images</label>
                <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.gif,.webp" class="file-input">
                <p class="help-text">You can select multiple images (JPG, PNG, GIF, WebP). Images will be added to the
                    end of your current list.</p>
                <button type="submit" class="upload-btn">Upload Images</button>
            </form>
        </div>
        <div class="card">
            <div class="card-title">Current Images</div>
            <?php if (!empty($pictures)): ?>
                <div class="img-grid">
                    <?php foreach ($pictures as $index => $picture): ?>
                        <div class="img-box">
                            <img src="<?= $web_root . $picture->picture_path ?>" alt="Image <?= $picture->priority ?>"
                                class="img-thumb">
                            <div class="img-actions">
                                <?php if ($picture->priority > 1): ?>
                                    <a href="manage_images/move_left/<?= $item->get_Id() ?>/<?= $picture->priority ?>"
                                        class="action-btn">←</a>
                                <?php else: ?>
                                    <span class="action-btn disabled">←</span>
                                <?php endif; ?>
                                <?php if ($picture->priority < $picture_count): ?>
                                    <a href="manage_images/move_right/<?= $item->get_Id() ?>/<?= $picture->priority ?>"
                                        class="action-btn">→</a>
                                <?php else: ?>
                                    <span class="action-btn disabled">→</span>
                                <?php endif; ?>                     
                                <a href="manage_images/delete/<?= $item->get_Id() ?>/<?= $picture->priority ?>"
                                    class="action-btn del">✕</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color: #9ca3af; text-align: center;">No images yet.</p>
            <?php endif; ?>
        </div>