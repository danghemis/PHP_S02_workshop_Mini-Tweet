<?php

class Message extends ActiveRecord
{
    static $table = 'Messages';

    private $id;

    private $senderId;

    private $receiverId;

    private $date;

    private $text;

    private $stat;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getSenderId()
    {
        return $this->senderId;
    }

    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
    }

    public function getReceiverId()
    {
        return $this->receiverId;
    }

    public function setReceiverId($receiverId)
    {
        $this->receiverId = $receiverId;
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

    public function getStat()
    {
        return $this->stat;
    }

    public function setStatus($stat)
    {
        $this->stat = $stat;
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
                'sender_id'=> $this->getSenderId(),
                'receiver_id' => $this->getReceiverId(),
                'text' => $this->getText(),
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
        $messageList = self::$conn->query($sql);
        $messages = [];

        foreach ($messageList as $messageInfo) {
            $messages[] = new Message($messageInfo['text'], $messageInfo['sender_id'], $messageInfo['receiver_id'], $messageInfo['date']);
        }

        return $messages;
    }

}