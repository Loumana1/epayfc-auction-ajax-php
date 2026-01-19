<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Item - Confirmation</title>
    <base href="<?= $web_root ?>">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <header>
        <div class="header-content">
            <a href="open_item/index/<?= $item->get_Id() ?>" class="header-back-btn">←</a>
            <h1 class="header-title">Delete Item</h1>
            <div class="header-spacer"></div>
        </div>
    </header>

        <main class="delete-confirm-container">
        <div class="delete-confirm-box">

        <div class="icon-delete-container">

            <i class="bi bi-trash" ></i>

        </div>
            


        <h2 >Are you sure?</h2>

        <hr class="divider-line" style="height: 1px; width: 80%; margin: 20px auto;">
    
     
        <p>Do you really want to delete item 
       
            <strong>"<?= $item->get_Title() ?>"</strong> 
            by <strong> <?= $seller ? $seller->get_FullName() : 'unknom'   ?></strong>
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

    
    <nav class="navBar navBar-principal">


            <a href="browse_items" >
            <i class="bi bi-search"></i>
                <span>Browse</span>
            </a>
       
                <a>

                <i class="bi bi-house"></i>
                    <span>My Items</span>
                </a>
            <a>
            <i class="bi bi-plus-circle"></i>
                <span>Add Offer</span>
            </a>
                <a>
                <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
    

           
    </main>
</body>
</html>