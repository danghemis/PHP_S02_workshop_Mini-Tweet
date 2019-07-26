<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['content']) && $_POST['content'] !== '') {
        $myTweet = new Tweet($_POST['content']);
        $myTweet->setUser($loggedUser)
                ->setDate(time());
        $myTweet->save();
    }
}

$tweetList = Tweet::findAll();
?>
<html>
<head>
    <title>My Twitter</title>
    <style>
        .tweetlist > .tweet {
            display: block;
            width: 100%;
            margin-bottom: 15px;
        }

        .tweetlist > .tweet > .content {
            font-size: 1.3em;
        }

        .tweetlist > .tweet > .user {
            font-style: italic;
            color: #888;
        }
    </style>
</head>
<body>
<div>
    <div class="page-header">
        Salutare, <?php
            echo $loggedUser->getName();
        ?>!

        <a href="index.php?page=logout">logout</a>
    </div>
    <form action="index.php" method="post">
        <textarea name="content" id="" cols="30" rows="10"></textarea>
        <button type="submit">Send tweet!</button>
    </form>

    <div class="tweetlist">
        <?php foreach ($tweetList as $tweet) { ?>
        <div class="tweet">
            <div class="content"><?php echo $tweet->getContent()?></div>
            <div class="user"><?php echo $tweet->getUser()->getName() . ' at ' . date('Y-m-d h:i:s', $tweet->getDate()); ?></div>
<!--            <div><a href="index.php">adauga comentariu!</a></div>-->
        </div>
        <?php } ?>
    </div>

</div>
</body>
</html>
