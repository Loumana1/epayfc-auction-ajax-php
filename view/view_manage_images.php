<?php ob_start(); ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.min.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Manage Images</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/manage.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<div class="manage-page">
<body class="manage-page" data-item-id="<?= $item->get_Id() ?>">

    <main>
        <h1 class="page-subtitle">
            Manage Images for "<?= htmlspecialchars($item->get_Title()) ?>"
        </h1>

        <?php include __DIR__ . "/partials/manage_images/_manage_images.php"; ?>
    </main>
        
</div>
<?php $content = ob_get_clean(); ?>
<?php require "view_layout.php"; ?>

    
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

    
    <script src="js/manage_images.js"></script>

</body>
</html>