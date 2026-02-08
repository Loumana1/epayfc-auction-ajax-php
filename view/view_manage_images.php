<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <title>Manage Images</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/manage.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</head>

<body class="manage-page">

    <main>
        <h1 class="page-subtitle">Manage Images for "<?= htmlspecialchars($item->get_Title()) ?>"</h1>
        
             <?php include __DIR__ . "/partials/manage_images/_manage_images.php"; ?>

    </main>
        
</body>
</html>