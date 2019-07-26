<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && $_POST['email'] !== '' &&
        isset($_POST['password']) && $_POST['password'] !== ''
    ) {
        $user = User::findOneByEmail($_POST['email']);
        if (!is_null($user) && $user->login($_POST['password'])) {
            $_SESSION['user'] = $user->getId();

            header('Location: index.php');
        }
    }
}

?>
<html>
<head>
    <title>Login</title>
</head>
<body>
<form action="index.php?page=login" method="post">
    <input type="text" name="email">
    <input type="password" name="password">
    <button type="submit">Log in</button>
</form>

<a href="index.php?page=register">Register now!</a>
</body>
</html>