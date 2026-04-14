<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $item_id ? "Edit item" : "Add item" ?></title>

    <base href="<?= htmlspecialchars($web_root) ?>">
    <!---CSS bootstrao pour modal-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    
    <link rel="stylesheet" href="css/add_edit_item.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!--Modal bootstrap pour exit-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

<div class="page">

    <header class="top-bar">
        <a href="item/my_items" class="back">
            
            <i class="bi bi-arrow-left"></i>
        </a>

        <h1><?= $item_id ? "Edit item" : "Add item" ?></h1>

        <button class="save-btn" form="item-form">
            <i class="bi bi-floppy"></i>
        </button>
    </header>


    <form id="item-form" method="post" action="item/add_edit_item<?= $item_id ? "/$item_id" : "" ?>">

        <section class="card">
            <h2>Basic Information</h2>

            <div class="field">
                <label>Item Title *</label>
                <input type="text" name="title" value="<?= $title ?>" placeholder="Ex: iPhone 13 Pro Max 256GB">
                <?php if (!empty($errors["title"])): ?>
                    <div class="error"><?= $errors["title"] ?></div>
                <?php endif; ?>
            </div>

            <div class="field">
                <label>Description</label>
                <textarea name="description" rows="4" placeholder="Describe your item in detail..."><?= $description ?></textarea>
                <?php if (!empty($errors["description"])): ?>
                    <div class="error"><?= $errors["description"] ?></div>
                <?php endif; ?>
            </div>

            <div class="field">
                <label>Sale Duration (days) *</label>
                <input type="number" name="duration_days" value="<?= $duration_days ?>">
                <?php if (!empty($errors["duration_days"])): ?>
                    <div class="error"><?= $errors["duration_days"] ?></div>
                <?php endif; ?>
            </div>
        </section>

        <section class="card">
            <h2>Sale Type</h2>

            <div class="sale-box auction">
                <h3>⚖️ Option 1: Auction</h3>

                <div class="field">
                    <label>Starting Bid</label>
                    <input type="text" name="starting_bid" value="<?= $starting_bid ?>" placeholder="e.g. 50.00">
                    <?php if (!empty($errors["starting_bid"])): ?>
                        <div class="error"><?= $errors["starting_bid"] ?></div>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label>Instant Purchase Price (optional)</label>
                    <input type="text" name="buy_now_price" value="<?= $buy_now_price ?>" placeholder="e.g. 200.00">
                    <?php if (!empty($errors["buy_now_price"])): ?>
                        <div class="error"><?= $errors["buy_now_price"] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="sale-box direct">
                <h3>🛒 Option 2: Direct Sale</h3>

                <div class="field">
                    <label>Sale Price</label>
                    <input type="text" name="sale_price" value="<?= $sale_price ?>" placeholder="e.g. 150.00">
                    <?php if (!empty($errors["buy_now_price"])): ?>
                        <div class="error"><?= $errors["buy_now_price"] ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

      
    </form>

    <!--Modal exit-->
    <div class="modal fade" id="unsaved-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Unsaved Changes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Leave with unsaved changes?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Stay</button>
                    <button type="button" class="btn btn-danger" id="unsaved-confirm-leave">Leave</button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . "/partials/_navbar.php"; ?>
<?php include __DIR__ . "/partials/_timebar.php"; ?>
    <!--Modal exit-->
<script src="js/add_edit_exit_confirmation.js"></script>
</body>
</html>
