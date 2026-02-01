<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <base href="/prwb_2526_c04/">

    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>

    <div class="top-title">
        <i class="bi bi-cart4"></i> EPayFC
    </div>

    <div class="card">
        <h2>Sign in</h2>

        <hr class="separator">

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $e): ?>
                    <?= $e ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="login/login">

            <div class="input-group">
                <span class="icon"><i class="bi bi-person"></i></span>
                <input type="text"
                       name="mail"
                       placeholder="Mail"
                       value="<?= $mail ?>">
            </div>

            <div class="input-group">
                <span class="icon"><i class="bi bi-key"></i></span>
                <input type="password"
                       name="password"
                       placeholder="Password">
            </div>

            <button class="btn btn-login" type="submit">Login</button>
        </form>

        <a class="btn btn-guest" href="test">Continue as guest</a>

        <p class="subscribe">
            <a href="user/register">New here ? Click here to subscribe !</a>
        </p>

        <hr class="separator">

        <?php if (Configuration::is_dev()): ?>
            <div class="debug">
                <div class="debug-title">For Debug Purpose</div>

                <a href="login/login_as/boverhaegen@epfc.eu">Login as boverhaegen@epfc.eu</a><br>
                <a href="login/login_as/mamichel@epfc.eu">Login as mamichel@epfc.eu</a><br>
                <a href="login/login_as/quhouben@epfc.eu">Login as quhouben@epfc.eu</a><br>
                <a href="login/login_as/xapigeolet@epfc.eu">Login as xapigeolet@epfc.eu</a>
            
               

                <a href="setup/install" class="debug-action debug-ok">
                    Restore original data
                </a>

                <a href="setup/export" class="debug-action debug-warn">
                    Backup personal data
                </a>

                <a href="setup/restore" class="debug-action debug-warn">
                    Restore personal data
                </a>


            </div>
        <?php endif; ?>
    </div>

</body>
</html>
