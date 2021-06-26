<?php

class Message extends ActiveRecord
{
    static $table = 'Messages';
    private $id;
    private $senderId;
    private $receiverId;
    /** @var User $sender */
    private $sender = null;
    /** @var User $receiver */
    private $receiver = null;
    private $text;
    private $date;
    private $stat;

    public function __construct($id = null)
    {
        $this->setId($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
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

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getSenderId()
    {
        return $this->senderId;
    }

    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
        return $this;
    }

    public function getReceiverId()
    {
        return $this->receiverId;
    }

    public function setReceiverId($receiverId)
    {
        $this->receiverId = $receiverId;
        return $this;
    }

    public function getStatus()
    {
        return $this->stat;
    }

    public function setStatus($stat)
    {
        $this->stat = $stat;
        return $this;
    }

    public function getSender()
    {
        if ($this->sender === null) {
            $sender = User::findOneById($this->getSenderId());
            $this->setSender($sender);
        }
        return $this->sender;
    }

    public function setSender(User $user)
    {
        $this->sender = $user;
        $this->setSenderId($user->getId());
        return $this;
    }

    public function getReceiver()
    {
        if ($this->receiver === null) {
            $receiver = User::findOneById($this->getReceiverId());
            $this->setReceiver($receiver);
        }
        return $this->receiver;
    }

    public function setReceiver(User $user)
    {
        $this->receiver = $user;
        $this->setReceiverId($user->getId());
        return $this;
    }

    public function save()
    {
        if ($this->getId() === null) {
            $sql = "
                INSERT INTO " . self::$table . "
                    (sender_id, receiver_id, text, date)
                VALUES
                    (:sender_id, :receiver_id, :text, :date);
            ";

            $deInlocuit = [
                'sender_id'=> $this->getSender()->getId(),
                'receiver_id' => $this->getReceiver()->getId(),
                'text' => $this->getText(),
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

    public static function findAllBySender(User $user)
    {
        $messages = [];
        $sql = 'SELECT * FROM ' . self::$table . ' WHERE sender_id = :sender_id ORDER BY date DESC';
        $statement = self::$conn->prepare($sql);
        $queryParam = (
        ['sender_id' => $user->getId()]
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
                $message = new Message($row['id']);
                $message->setText($row['text'])->setDate($row['date'])->setSenderId($row['sender_id'])->setReceiverId($row['receiver_id']);
                $messages[] = $message;
            }
            return $messages;
        } else {
            return null;
        }
    }

    public static function findAllByReceiver(User $user)
    {
        $messages = [];
        $sql = 'SELECT * FROM ' . self::$table . ' WHERE receiver_id = :receiver_id ORDER BY date DESC';
        $statement = self::$conn->prepare($sql);
        $queryParam = (
        ['receiver_id' => $user->getId()]
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
                $message = new Message($row['id']);
                $message->setText($row['text'])->setDate($row['date'])->setSenderId($row['sender_id'])->setReceiverId($row['receiver_id']);
                $messages[] = $message;
            }
            return $messages;
        } else {
            return null;
        }
    }

}