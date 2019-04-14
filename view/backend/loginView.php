<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="public/css/fonts.css" rel="stylesheet" />
        <link href="public/css/backend.css" rel="stylesheet" /> 
    </head>
    <body>
        <h1>Login</h1>
        <form id="login_form" action="admin.php?action=loginAdmin" method="post">
            <input id="username" type="username" name="username" placeholder="Identifiant" />
            <input id="password" type="password" name="password" placeholder="Mot de passe" />
            <button type="submit" id="btn_login">Se connecter</button>
        </form>
        <div id="error_login">Identifiant ou mot de passe incorrect</div>
        
        <script src="https://code.jquery.com/jquery.min.js" ></script>
        <script src="public/js/login.js"></script>
    </body>
</html>