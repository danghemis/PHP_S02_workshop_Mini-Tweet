<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['submit'] == 'tweet') {
        if (isset($_POST['content']) && $_POST['content'] !== '') {
            $myTweet = new Tweet();
            $myTweet->setContent($_POST['content'])->setDate(date('Y-m-d H:i:s', time()))->setUser($loggedUser);
            $myTweet->save();
        }
    }
    if ($_POST['submit'] == 'comment') {
        //var_dump($_POST);
        if (isset($_POST['text']) && $_POST['text'] !== '' && isset($_POST['tweet_id'])) {
            $myComment = new Comment();
            $myComment->setText($_POST['text'])->setUser($loggedUser)->setTweetId($_POST['tweet_id'])->setDate(date('Y-m-d H:i:s', time()));
            $myComment->save();
        }
    }
}

$tweetList = Tweet::findAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Home</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <h3>Salutare, <?= $loggedUser->getName(); ?>!</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin-top: 10px; margin-bottom: 10px;">
                <a class="btn btn-warning" href="index.php?page=logout" role="button">Logout</a>
                <a class="btn btn-info" href="index.php?page=messages" role="button">Mesaje</a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <form action="index.php?page=homepage" method="post" role="form">
                    <legend>Tweet-ul tau</legend>
                    <div class="form-group">
                        <textarea required name="content" id="content" cols="30" rows="10" class="form-control" placeholder="tweet-ul tau..."></textarea>
                    </div>
                    <button type="submit" name="submit" value="tweet" class="btn btn-success">Posteaza tweet-ul!</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin-top: 10px;">
            <legend>All tweets</legend>
            <?php
            foreach ($tweetList as $tweet)
            {
                echo "
                    <div class=\"tweet\">
                        <h4 class=\"content\">" . $tweet->getContent() . "</h4>
                        <h5 class=\"user\">" . $tweet->getUser()->getName() . " at " . $tweet->getDate() . "</h5>
                    </div>
                    <hr>
                <div class=\"commentList\">
                    <p>Comentarii:</p>
                    <div class=\"comment\">
                    ";
                $commentList = Comment::findAllByTweet($tweet);
                if ($commentList != null) {
                    foreach($commentList as $comment)
                    {
                        echo "
                            <div class=\"text\">" . $comment->getText() . "</div>
                            <div class=\"user\">" . $comment->getUser()->getName() . " at " . $comment->getDate() . "</div>
                        ";
                    }
                } else {
                    echo "<p>Nu sunt comentarii. Fii primul care comenteaza la acest tweet!</p>";
                }
                echo "
                    </div>
                </div>
                ";
                echo "
                    <form action=\"index.php?page=homepage\" method=\"post\">
                        <input type=\"text\" name=\"tweet_id\" id=\"tweet_id\" value=\"" . $tweet->getId() . "\" style=\"display: none;\">
                        <textarea name=\"text\" id=\"text\" cols=\"30\" rows=\"2\"></textarea>
                        <button type=\"submit\" name=\"submit\" value=\"comment\">Add comment!</button>
                    </form>
                    <hr>
                ";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
