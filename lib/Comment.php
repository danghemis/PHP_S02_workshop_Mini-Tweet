<?php

class Comment extends ActiveRecord
{
    static $table = 'Comments';
    private $id;
    private $text;
    private $date;
    /** @var User $user */
    private $user = null;
    private $userId;
    /** @var Tweet $tweet */
    private $tweet = null;
    private $tweetId;


    public function __construct($id = null)
    {
        $this->setId($id);
    }

    public function getId()
    {
        return $this->id;
    }

    private function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getTweetId()
    {
        return $this->tweetId;
    }

    public function setTweetId($postId)
    {
        $this->tweetId = $postId;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getUser()
    {
        if ($this->user === null) {
            $user = User::findOneById($this->getUserId());
            $this->setUser($user);
        }
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $this->setUserId($user->getId());
        return $this;
    }

    public function getTweet()
    {
        if ($this->tweet === null) {
            $tweet = Tweet::findOneById($this->getTweetId());
            $this->setTweet($tweet);
        }
        return $this->tweet;
    }

    public function setTweet(Tweet $tweet)
    {
        $this->tweet = $tweet;
        $this->setTweetId($tweet->getId());
        return $this;
    }

    public function save()
    {
        if ($this->getId() === null) {
            $sql = "
                INSERT INTO " . self::$table . "
                    (text, user_id, tweet_id, date)
                VALUES
                    (:text, :user_id, :tweet_id, :date);
            ";

            $deInlocuit = [
                'text' => $this->getText(),
                'user_id' => $this->getUser()->getId(),
                'tweet_id' => $this->getTweet()->getId(),
                'date' => $this->getDate()
            ];
            $query = self::$conn->prepare($sql);
            try {
                $status = $query->execute($deInlocuit);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }

        if ($status === true && $this->getId() === null) {
            $this->setId(self::$conn->lastInsertId());
        }

        return true;
    }

    public static function findAll()
    {
        $sql = 'SELECT * FROM ' . self::$table . ' ORDER BY date DESC';
        $commentList = self::$conn->query($sql);
        $comments = [];

        if ($commentList->rowCount() > 0)
        {
            foreach ($commentList as $commentInfo) {
                $comment = new Comment($commentInfo['id']);
                $comment->setText($commentInfo['text'])->setUserId($commentInfo['user_id'])->setTweetId($commentInfo['tweet_id'])->setDate($commentInfo['date']);
                $comments[] = $comment;
            }
            return $comments;
        } else {
            return null;
        }

    }

    public static function findAllByTweet(Tweet $tweet)
    {
        $commentList = [];
        $sql = 'SELECT * FROM ' . self::$table . ' WHERE tweet_id = :tweet_id ORDER BY date DESC';
        $statement = self::$conn->prepare($sql);
        $queryParam = (
            ['tweet_id' => $tweet->getId()]
        );

        try {
            $result = $statement->execute($queryParam);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        if ($statement->rowCount() > 0)
        {
            foreach ($statement as $row)
            {
                $comment = new Comment($row['id']);
                $comment->setText($row['text'])->setDate($row['date'])->setUserId($row['user_id'])->setTweetId($row['tweet_id']);
                $commentList[] = $comment;
            }
            return $commentList;
        } else {
            return null;
        }

    }

}
