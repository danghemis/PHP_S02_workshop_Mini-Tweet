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
<html>
<head>
    <title>Register now</title>
    <style>
        .form-field {
            display: block;
            width: 100%;
        }
    </style>
</head>
<body>
<p>Become a member today!</p>

<form action="index.php?page=register" method="post">
    <div class="form-field">
        <label for="name">Name:</label>
        <input type="text" name="name">
    </div>
    <div class="form-field">
        <label for="name">Email:</label>
        <input type="text" name="email">
    </div>
    <div class="form-field">
        <label for="name">Password:</label>
        <input type="password" name="password">
    </div>
    <button type="submit">Register!</button>
</form>

<p>
    I already have an account, take me back to <a href="index.php?page=login">login</a>!
</p>
</body>
</html>
