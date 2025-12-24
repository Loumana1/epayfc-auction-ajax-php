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
         <a href="user/register">New here ? Click here to subscribe !</a>
    </p>
    
    <?php if (Configuration::is_dev()): ?>
        <div style="margin-top:15px; border-top: 1px solid #444; padding-top: 10px;">
            <h3 style="text-align:center; color: #aaa;"> For Debug Purpose </h3>
            <div style="text-align:center;">
                <a href="login/login_as/boverhaegen@epfc.eu" style="display:block; margin-bottom:3px;">login as boverhaegen@epfc.eu</a>   
                <a href="login/login_as/quhouben@epfc.eu" style="display:block; margin-bottom:3px;">Login as quhouben@epfc.eu</a>
                <a href="login/login_as/mamichel@epfc.eu" style="display:block; margin-bottom:3px;">login as mamichel@epfc.eu</a>
                <a href="login/login_as/xapigeolet@epfc.eu" style="display:block; margin-bottom:3px;">login as xapigeolet@epfc.eu</a>
                
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
