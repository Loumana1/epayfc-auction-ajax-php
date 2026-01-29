<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Item</title>
    <base href="<?= $web_root ?>">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <!------HEADER---------->
        <?php include __DIR__ . "/partials/_header.php"; ?>

        <main class="delete-confirm-container">
        <div class="delete-confirm-box">

        <div class="icon-delete-container">

            <i class="bi bi-trash" ></i>

        </div>
            


        <h2 >Are you sure?</h2>

        <hr class="divider-line" style="height: 1px; width: 80%; margin: 20px auto;">
    
     
        <p>Do you really want to delete item 
       
            <strong>"<?= $item->get_Title() ?>"</strong> 
            by <strong> <?= $seller ? $seller->get_FullName() : 'unknown'   ?></strong>
            and all of its dependencies?
        </p>
        <p>This process cannot be undone.</p>
        
    <!-- Boutons -->
    <div class="action-buttons">
        <a href="open_item/index/<?= $item->get_Id() ?>" class="btn btn-cancel">Cancel</a>
        <form method="post" action="delete_confirm/confirm" style="display: inline;">
            <input type="hidden" name="item_id" value="<?= $item->get_Id() ?>">
            <button type="submit" class="btn btn-delete">Delete</button>
        </form>
    </div>
</div>



           
    </main>
    <?php include __DIR__ . "/partials/_navbar.php"; ?>
    <?php include __DIR__ . "/partials/_timebar.php"; ?>

</body>
</html>