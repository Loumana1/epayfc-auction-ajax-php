<div class="card">
    <div class="card-title">Add New Images</div>
    <form action="manage_images/upload/<?= $item->get_Id() ?>" method="post" enctype="multipart/form-data">
        <label class="form-label">Select Images</label>
        <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.gif,.webp" class="file-input">
        <p class="help-text">
            You can select multiple images (JPG, PNG, GIF, WebP). 
            Images will be added to the end of your current list.
        </p>
        <button type="submit" class="upload-btn">Upload Images</button>
    </form>
</div>

<div class="card">
    <div class="card-title">Current Images</div>
    <?php if (!empty($pictures)): ?>
        <div class="img-grid" id="sortable-images" data-item-id="<?= $item->get_Id() ?>">
            <?php foreach ($pictures as $index => $picture): ?>
                <?php 
                    $item_id = $item->get_Id();
                    $priority = $picture->priority;
                    $base_url = "manage_images";
                ?>
                <div class="img-box" data-path="<?= $picture->picture_path ?>">
                    <img src="<?= $web_root . $picture->picture_path ?>" 
                         alt="Image <?= $priority ?>" class="img-thumb">
                    
                    <div class="img-actions">
                        <button class="action-btn btn-left <?= $priority <= 1 ? 'disabled' : '' ?>" <?= $priority <= 1 ? 'disabled' : '' ?> data-priority="<?= $priority ?>">←</button>
                        <noscript>
                            <?php if ($priority > 1): ?>
                                <form action="<?= $base_url ?>/move_left/<?= $item_id ?>/<?= $priority ?>" method="POST" style="display:inline-block;">
                                 
                                    <input type="hidden" name="encoded_state" value="<?= $encoded_state ?? '' ?>">
                                    <button type="submit" class="action-btn">←</button>
                                </form>
                            <?php else: ?>
                                <span class="action-btn disabled">←</span>
                            <?php endif; ?>
                        </noscript>

                        <button class="action-btn btn-right <?= $priority >= $picture_count ? 'disabled' : '' ?>" <?= $priority >= $picture_count ? 'disabled' : '' ?> data-priority="<?= $priority ?>">→</button>
                        <noscript>
                            <?php if ($priority < $picture_count): ?>
                                <form action="<?= $base_url ?>/move_right/<?= $item_id ?>/<?= $priority ?>" method="POST" style="display:inline-block;">
                                <input type="hidden" name="encoded_state" value="<?= $encoded_state ?? '' ?>">
                                    <button type="submit" class="action-btn">→</button>
                                </form>
                            <?php else: ?>
                                <span class="action-btn disabled">→</span>
                            <?php endif; ?>
                        </noscript>                     
                        
                        <button class="action-btn del btn-delete" data-priority="<?= $priority ?>">✕</button>
                        <noscript>
                            <form action="<?= $base_url ?>/delete/<?= $item_id ?>/<?= $priority ?>" method="POST" style="display:inline-block;">
                            <input type="hidden" name="encoded_state" value="<?= $encoded_state ?? '' ?>">
                                <button type="submit" class="action-btn del">✕</button>
                            </form>
                        </noscript>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="color: #9ca3af; text-align: center;">No images yet.</p>
    <?php endif; ?>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-bg-dark" style="border: 1px solid #333;">
            
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fs-6">Delete image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete this image?
            </div>

            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #4b5563; border-color: #4b5563;">
                    Cancel
                </button>
                <button type="button" id="confirmDelete" class="btn btn-danger">
                    Delete
                </button>
            </div>

        </div>
    </div>
</div>