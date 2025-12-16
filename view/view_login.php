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
    <div>
        <h1 class="bi bi-cart4"
            style = "text-align:center;"> EPayFC
        </h1>
    </div>

<div class="card">

    <h2 style="text-align:center;">Sign in</h2>

   
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $e): ?>
                <?= $e ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="login/login" >

        <input type="text"
               name="mail"
               placeholder="Mail"
               value="<?= $mail ?>">

        <input type="password"
               name="password"
               placeholder="Password">

        <button class="btn btn-login" type="submit">Login</button>

    </form>

    <p style="margin-top:15px; text-align:center;">
        New here ? <a href="user/register">Click here to subscribe !</a>
    </p>

</div>

</body>
</html>
