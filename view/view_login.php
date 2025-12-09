<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet" href="/view/css/login.css">
</head>

<body>

<div class="card">

    <h2 style="text-align:center;">Sign in</h2>

   
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $e): ?>
                <?= $e ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="login/login" method="post">

        <input type="text"
               name="mail"
               placeholder="Mail"
               value="<?= htmlspecialchars($mail) ?>">

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
