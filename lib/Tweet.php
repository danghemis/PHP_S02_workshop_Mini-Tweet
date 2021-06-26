<?php

class Tweet extends ActiveRecord
{
    static $table = 'Tweets';
    private $id;
    private $content;
    private $date;
    /** @var User $user */
    private $user = null;
    private $userId;

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

    public function save()
    {
        if ($this->getId() === null) {
            $sql = "
                INSERT INTO " . self::$table . "
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
            $eachTweet = new Tweet($tweetData['id']);
            $eachTweet->setUserId($tweetData['user_id'])->setContent($tweetData['content'])->setDate($tweetData['date']);
            $tweets[] = $eachTweet;
        }

        return $tweets;
    }

    public static function findOneById($id)
    {
        $sql = "SELECT * FROM " . self::$table . " WHERE id = :id;";
        $deInlocuit = ['id' => $id];

        $list = self::$conn->prepare($sql);

        try {
            $result = $list->execute($deInlocuit);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        if ($list->rowCount() > 1) {
            die('nu se poate gasi un singur tweet');
        } elseif ($list->rowCount() === 0) {
            return null;
        } else {
            $row = $list->fetch(PDO::FETCH_ASSOC);

            $returnableTweet = new Tweet($row['id']);
            $returnableTweet
                ->setContent($row['content'])
                ->setDate($row['date'])
                ->setUserId($row['user_id']);

            return $returnableTweet;
        }
    }

}