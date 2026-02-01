<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/edit_Profile.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="edit-profile-page">

    <div class="top-header">

        <?php include __DIR__ . "/partials/_header.php"; ?>

        <button type="submit" form="edit-form" class="save-btn">💾</button>

    </div>

    <main>
            <?php include __DIR__ . "/partials/edit_profile/_edit_profile.php"; ?>

    </main>

        <?php include __DIR__ . "/partials/_navbar.php"; ?>

        <?php include __DIR__ . "/partials/_timebar.php"; ?>

</body>

</html>