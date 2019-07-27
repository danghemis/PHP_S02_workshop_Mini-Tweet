<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['content']) && $_POST['content'] !== '') {
        $myTweet = new Tweet($_POST['content']);
        $myTweet->setUser($loggedUser)
                ->setDate(time());
        $myTweet->save();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['text']) && $_POST['text'] !== '') {
        $myComment = new Comment($_POST['text']);
        $myComment->setUser($loggedUser)
            ->setDate(time());
        $myComment->save();
    }
}

$tweetList = Tweet::findAll();

$commentList = Comment::findAll();
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
            font-size: 1.4em;
        }

        .tweetlist > .tweet > .user {
            font-style: italic;
            color: #888;
        }
        .commentList{
            font-size: 1em;
            color: darkred;
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
        <?php foreach ($tweetList as $tweet) {  $tweet->getComments ();?>
        <div class="tweet">
            <div class="content"><?php echo $tweet->getContent()?></div>
            <div class="user"><?php echo $tweet->getUser()->getName() . ' at ' . date('Y-m-d h:i:s', $tweet->getDate()); ?></div>
<!--            <div><a href="index.php">adauga comentariu!</a>-->
        </div>
    </div>
                <form action="index.php" method="post">
                    <textarea name="text" id="" cols="30" rows="2"></textarea>
                    <button type="submit">Add comment!</button>
                </form>
            <div class="commentList">

            <div class="comment">
                <?php foreach($commentList as $comment) { ?>
                <div class="text"><?php echo $comment->getText()?></div>
                <div class="user"><?php echo $comment->getUser()->getName() . ' at ' . date('Y-m-d h:i:s', $comment->getDate()); ?></div>

             </div>
        <?php } } ?>
            </div>

    </div>
</div>
</body>
</html>
