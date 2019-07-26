<?php
session_start();
require_once 'setup.php';

$loggedUser = null;
if (isset($_SESSION['user'])) {
    /** @var User $loggedUser */
    $loggedUser = User::findOneById($_SESSION['user']);
}
$page = null;

if (isset($_GET['page']) && $_GET['page'] !== '') {
    $page = $_GET['page'];
}
switch ($page) {
    case 'register':
        include 'pages/register.php';
        break;
    case 'logout':
        unset($_SESSION['user']);
        header('Location: index.php');
        break;
    case 'messages':
        include 'pages/messages.php';
        break;
    case 'homepage':
    case 'login':
    case '':
    case null:
        if ($loggedUser === null) {
            include 'pages/login.php';
        } else {
            include 'pages/homepage.php';
        }
        break;
}
