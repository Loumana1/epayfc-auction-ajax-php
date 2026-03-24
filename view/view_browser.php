<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <title>Browser</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/browser.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="js/search_filter.js"></script>

</head>
<body>
<main>
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search item"
            class="search-input">
            <i class="bi bi-search search-icon"></i>
    </div>
    <?php include __DIR__ . "/partials/browser_item/_items_participating.php"; ?>

    <?php include __DIR__ . "/partials/browser_item/_other_items.php"; ?>

</main>

        </body>
</html>
