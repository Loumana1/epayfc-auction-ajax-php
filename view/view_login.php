<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <style>
        body {
            background-color: #1a2433;
            font-family: Arial, sans-serif;
            color: white;
        }

        .card {
            width: 350px;
            margin: 80px auto;
            background: #2e3645;
            padding: 30px;
            border-radius: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            border-radius: 6px;
            border: none;
        }

        .btn {
            width: 100%;
            margin-top: 15px;
            padding: 10px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .btn-login {
            background-color: #4a90e2;
            color: white;
        }

        .error {
            background: #c0392b;
            color: white;
            padding: 8px;
            margin-top: 15px;
            border-radius: 6px;
        }

        a { color: #4aa3ff; }
    </style>
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
