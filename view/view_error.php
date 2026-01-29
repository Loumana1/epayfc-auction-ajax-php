<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/error.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

    <main class="error-container">
        
        <div class="error-icon-wrapper">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        
        <h1 class="error-oops">Oops!</h1>
        
        <p class="error-tip-text">An unexpected error occurred.</p>
        
        <div class="error-box">
            <div class="error-details-box">
                <?= $error ?>
            </div>
        </div>
        
        <a href="browser" class="btn-error-browse">
            Back to Browser
        </a>
        
    </main>

</body>
</html>