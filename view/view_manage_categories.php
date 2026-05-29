<?php ob_start(); ?>

<div class="manage-categories-page">
    <h2>Manage Categories</h2>

    <div class="categories-list" id="categories-list">
        <?php foreach ($categories as $i => $cat): ?>
        <div class="category-row" data-id="<?= $cat->id ?>" data-count="<?= $cat->item_count ?>">

            <span class="drag-handle" title="Drag to reorder">&#8942;&#8942;</span>

            <form id="save-form-<?= $cat->id ?>" method="post" action="category/save/<?= $cat->id ?>" class="name-form">
                <div class="name-field-wrap">
                    <input type="text"
                           name="name"
                           value="<?= htmlspecialchars($cat->name) ?>"
                           class="category-name-input"
                           maxlength="25">
                    <?php if (!empty($save_errors[$cat->id])): ?>
                        <div class="field-error">
                            <?php foreach ($save_errors[$cat->id] as $e): ?>
                                <?= htmlspecialchars($e) ?><br>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <span class="cat-label"><?= htmlspecialchars($cat->name) ?></span>
                <span class="cat-count">(<?= $cat->item_count ?>)</span>
            </form>

            <div class="row-actions">
                <form method="post" action="category/move_up/<?= $cat->id ?>">
                    <button type="submit" class="btn-move" <?= $i === 0 ? 'disabled' : '' ?>>Up</button>
                </form>
                <form method="post" action="category/move_down/<?= $cat->id ?>">
                    <button type="submit" class="btn-move" <?= $i === count($categories) - 1 ? 'disabled' : '' ?>>Down</button>
                </form>
                <button type="submit" form="save-form-<?= $cat->id ?>" class="btn-save">Save</button>
                <button class="btn-edit">Edit</button>
                <?php if ($cat->item_count === 0): ?>
                    <a href="category/delete_confirm/<?= $cat->id ?>" class="btn-delete">Delete</a>
                <?php else: ?>
                    <button class="btn-delete" disabled>Delete</button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <form method="post" action="category/add" class="add-form">
        <div class="name-field-wrap">
            <input type="text"
                   name="name"
                   value="<?= htmlspecialchars($new_name ?? '') ?>"
                   placeholder="Category name"
                   maxlength="25"
                   class="category-name-input"
                   id="new-category-input">
            <?php if (!empty($add_errors)): ?>
                <div class="field-error">
                    <?php foreach ($add_errors as $e): ?>
                        <?= htmlspecialchars($e) ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn-add-submit">+</button>
    </form>
</div>

<!-- Bootstrap modal de confirmation de suppression (JS uniquement) -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Delete category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Do you really want to delete category "<strong id="modal-cat-name"></strong>"?<br>
                This action cannot be undone.
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="modal-confirm-delete">Delete</button>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require "view_layout.php"; ?>
