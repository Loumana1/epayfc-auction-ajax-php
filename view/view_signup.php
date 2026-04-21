<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sign_up.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>

    <div class="top-title">
        <i class="bi bi-cart4"></i> EPayFC
    </div>

   <?php include __DIR__ . "/partials/sign_up/_sign_up.php"; ?>

</body>

</html>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>window.APP_BASE = '<?= $web_root ?>';</script>
<script src="js/user_validation.js"></script>