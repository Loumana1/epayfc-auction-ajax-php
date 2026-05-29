<?php ob_start(); ?>

<div class="delete-confirm-page">
    <div class="confirm-card">
        <div class="confirm-icon">
            <i class="bi bi-trash3"></i>
        </div>
        <h2>Are you sure?</h2>
        <hr>
        <p>Do you really want to delete category "<strong><?= htmlspecialchars($category->name) ?></strong>"?<br>
        This process cannot be undone.</p>
        <div class="confirm-actions">
            <a href="category/manage" class="btn btn-secondary">Cancel</a>
            <form method="post" action="category/delete/<?= $category->id ?>">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require "view_layout.php"; ?>
