<?php

class Tweet extends ActiveRecord
{
    static $table = 'Tweets';

    private $id;

    private $content;

    /** @var User $user */
    private $user = null;

    private $userId;

    private $date;

    private $comments = [];

    public function __construct($content = null, $id = null, $userId = null, $date = null)
    {
        $this->setId($id);
        $this->setUserId($userId);
        $this->setContent($content);
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

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
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

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    private function getUserId()
    {
        return $this->userId;
    }

    private function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getComments()
    {
        // Comment::findAllByTweetId($this->getId())

        return $this->comments;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    public function save()
    {
        if ($this->getId() === null) {
            $sql = "
                INSERT INTO ".self::$table."
                    (content, user_id, date)
                VALUES
                    (:content, :user_id, :date);
            ";

            $deInlocuit = [
                'content' => $this->getContent(),
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
        $tweetList = self::$conn->query($sql);
        $tweets = [];

        foreach ($tweetList as $tweetData) {
            $tweets[] = new Tweet($tweetData['content'], $tweetData['id'], $tweetData['user_id'], $tweetData['date']);
        }

        return $tweets;
    }
}