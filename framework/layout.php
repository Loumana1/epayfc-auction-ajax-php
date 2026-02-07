<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($header_title ?? 'Page') ?></title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <?php foreach ($page_css ?? [] as $css): ?>
    <link rel="stylesheet" href="css/<?= htmlspecialchars($css) ?>">
    <?php endforeach; ?>
</head>
<body>
<?php require __DIR__ . "/../view/partials/_header.php"; ?>

<?php require $view_content_file; ?>

<?php require __DIR__ . "/../view/partials/_navbar.php"; ?>
<?php require __DIR__ . "/../view/partials/_timebar.php"; ?>
</body>
</html>