<?php

if ($loggedUser !== null ) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name']) && $_POST['name'] !== '' &&
        isset($_POST['email']) && $_POST['email'] !== '' &&
        isset($_POST['password']) && $_POST['password'] !== ''
    ) {
        // aici lipseste, dar frumos ar fi sa ne
        // asiguram ca nu primim ceva malitios

        $newUser = new User();
        $newUser->setName($_POST['name'])
            ->setEmail($_POST['email'])
            ->setPassword($_POST['password']);

        if ($newUser->save()) {
            $loggedUser = $newUser->getId();
            $_SESSION['user'] = $newUser->getId();

            header('Location: index.php');
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
    <title>Register to MiniTweet</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <form action="index.php?page=register" method="post" role="form">
                <legend>Devino membru azi!</legend>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="nume...">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="email...">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="parola...">
                </div>
                <button type="submit" class="btn btn-success">Inregistreaza-te!</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <p>
                Am deja cont, du-ma inapoi la <a href="index.php?page=login">login</a>!
            </p>
        </div>
    </div>
</div>

</body>
</html>
