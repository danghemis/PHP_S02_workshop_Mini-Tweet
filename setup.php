<?php

require_once 'lib/ActiveRecord.php';
require_once 'lib/User.php';
require_once 'lib/Tweet.php';
require_once 'lib/Comment.php';
require_once 'lib/Message.php';

// vom face setup
$host = 'localhost';
$username = 'root';
$password = 'coderslab';
$database = 'Mini_Tweet';

//Below, write code that connects to the database
$dsn = "mysql:host=$host;dbname=$database;charset=utf8";
$conn = new PDO($dsn, $username, $password,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

if ($conn->errorCode() != null) {
    die("Connection failed. Error: " . $conn->errorInfo()[2]);
}

ActiveRecord::$conn = $conn;