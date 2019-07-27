<?php

class Comment extends ActiveRecord
{

    static $table = 'Comments';

    private $id;

    private $userId;

    private $postId;

    private $date;

    private $text;

    /** @var User $user */
    private $user = null;

    public function __construct($text = null, $id = null, $userId = null, $date = null)
    {
        $this->setId($id);
        $this->setUserId($userId);
        $this->setText($text);
        $this->setDate($date);
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


    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function setPostId($postId)
    {
        $this->postId = $postId;
    }

    public function getDate()
    {
        return $this->date;
    }


    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
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

    public function save()
    {
        if ($this->getId() === null) {
            $sql = "
                INSERT INTO " . self::$table . "
                    (text, user_id, date)
                VALUES
                    (:text, :user_id, :date);
            ";

            $deInlocuit = [
                'text' => $this->getText(),
                'user_id' => $this->getUser()->getId(),
                'date' => $this->getDate()
            ];
        }

        $query = self::$conn->prepare($sql);
        try {
            $status = $query->execute($deInlocuit);
        } catch (PDOException $e) {
            die($e->getMessage());
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

        foreach ($commentList as $commentInfo) {
            $comments[] = new Comment($commentInfo['text'], $commentInfo['id'], $commentInfo['user_id'], $commentInfo['date']);
        }

        return $comments;
    }
        public static function  fetchByTweet(Tweet $tweet){
        $result = 'SELECT * FROM Comments WHERE tweet_id=' . $tweet->getId();
        return $result;


    }

//    public static function findAllByTweetId()

}
