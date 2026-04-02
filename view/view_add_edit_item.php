<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $item_id ? "Edit item" : "Add item" ?></title>
    <base href="<?= Configuration::get('web_root') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/add_edit_item.css">
    <link rel="stylesheet" href="css/styles.css">
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


    <form id="item-form"
          method="post"
          action="item/add_edit_item<?= $item_id ? "/$item_id" : "" ?>"
          data-item-id="<?= $item_id ?? '' ?>">

        <section class="card">
            <h2>Basic Information</h2>

            <div class="field" id="field-title">
                <label>Item Title *</label>
                <input type="text" name="title" value="<?= htmlspecialchars($title) ?>"
                       placeholder="Ex: iPhone 13 Pro Max 256GB">
                <?php if (!empty($errors["title"])): ?>
                    <div class="error"><?= htmlspecialchars($errors["title"]) ?></div>
                <?php endif; ?>
            </div>

            <div class="field" id="field-description">
                <label>Description</label>
                <textarea name="description" rows="4"
                          placeholder="Describe your item in detail..."><?= htmlspecialchars($description) ?></textarea>
                <?php if (!empty($errors["description"])): ?>
                    <div class="error"><?= htmlspecialchars($errors["description"]) ?></div>
                <?php endif; ?>
            </div>

            <div class="field" id="field-duration">
                <label>Sale Duration (days) *</label>
                <input type="number" name="duration_days" value="<?= $duration_days ?>">
                <?php if (!empty($errors["duration_days"])): ?>
                    <div class="error"><?= htmlspecialchars($errors["duration_days"]) ?></div>
                <?php endif; ?>
            </div>
        </section>

        <section class="card">
            <h2>Sale Type</h2>

            <div class="sale-box auction">
                <h3>⚖️ Option 1: Auction</h3>

                <div class="field" id="field-starting-bid">
                    <label>Starting Bid</label>
                    <input type="text" name="starting_bid" value="<?= htmlspecialchars($starting_bid) ?>"
                           placeholder="e.g. 50.00">
                    <?php if (!empty($errors["starting_bid"])): ?>
                        <div class="error"><?= htmlspecialchars($errors["starting_bid"]) ?></div>
                    <?php endif; ?>
                </div>

                <div class="field" id="field-buy-now">
                    <label>Instant Purchase Price (optional)</label>
                    <input type="text" name="buy_now_price" value="<?= htmlspecialchars($buy_now_price) ?>"
                           placeholder="e.g. 200.00">
                    <?php if (!empty($errors["buy_now_price"])): ?>
                        <div class="error"><?= htmlspecialchars($errors["buy_now_price"]) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="sale-box direct">
                <h3>🛒 Option 2: Direct Sale</h3>

                <div class="field" id="field-sale-price">
                    <label>Sale Price</label>
                    <input type="text" name="sale_price" value="<?= htmlspecialchars($sale_price) ?>"
                           placeholder="e.g. 150.00">
                    <?php if (!empty($errors["buy_now_price"])): ?>
                        <div class="error"><?= htmlspecialchars($errors["buy_now_price"]) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </form>

</div>

<?php include __DIR__ . "/partials/_navbar.php"; ?>
<?php include __DIR__ . "/partials/_timebar.php"; ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.APP_BASE = '<?= Configuration::get('web_root') ?>';
</script>
<script src="js/item_validation.js"></script>

</body>
</html>
