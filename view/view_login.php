<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login </title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="titleLogin"> EPayFC</div>
    <div class="main">
          <form action="main/login" method="post">
                <table>
                    <thead>Sign in </thead>
                    <tr>
                        <td><input id="pseudo" name="pseudo" type="text"></td>
                    </tr>
                    <tr>
                        <td><input id="password" name="password" type="password"></td>
                    </tr>
                </table>
                <input type="submit" value="Log In">
            </form>
    </div>
</body>
</html>