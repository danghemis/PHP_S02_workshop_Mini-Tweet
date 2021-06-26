<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && $_POST['email'] !== '' &&
        isset($_POST['password']) && $_POST['password'] !== '')
    {
        $user = User::findOneByEmail($_POST['email']);
        if (!is_null($user) && $user->login($_POST['password']))
        {
            $_SESSION['user'] = $user->getId();
            header("Location: index.php");
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login to MiniTweet</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <form action="index.php?page=login" method="post" role="form">
                <legend>Bine ai venit la Mini-Tweet!</legend>
                <div class="form-group">
                    <input type="text" class="form-control" name="email" placeholder="email...">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="parola...">
                </div>
                <button type="submit" class="btn btn-success">Log in</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin-top:10px; margin-bottom:10px;">
            <a class="btn btn-info" href="index.php?page=register" role="button">Inregistreaza-te acum!</a>
        </div>
    </div>
</div>

</body>
</html>