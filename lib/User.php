<?php

class User extends ActiveRecord
{
    static $table = 'Users';

    private $id;

    private $name;

    private $email;

    private $password;

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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $savablePassword = password_hash($password, PASSWORD_BCRYPT);

        $this->password = $savablePassword;
        return $this;
    }

    public function login($password)
    {
        if (password_verify($password, $this->getPassword())) {
            return true;
        }

        return false;
    }

    public function save()
    {
        if ($this->getId() === null) {
            $sql = "
                INSERT INTO ".self::$table."
                    (name, email, password)
                VALUES
                    (:name, :email, :password);
            ";

            $deInlocuit = [
                'name' => $this->getName(),
                'email' => $this->getEmail(),
                'password' => $this->getPassword()
            ];
        } else {
            // fac update pentru userul cu id = getId()
            $sql = "
                UPDATE " . self::$table . "
                SET
                    name = :name,
                    email = :email,
                    password = :password
                WHERE
                    id = :id
            ";

            $deInlocuit = [
                'id' => $this->getId(),
                'name' => $this->getName(),
                'email' => $this->getEmail(),
                'password' => $this->getPassword()
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
            die('nu se poate gasi un singur utilizator');
        } elseif ($list->rowCount() === 0) {
            return null;
        } else {
            $row = $list->fetch(PDO::FETCH_ASSOC);

            $returnableUser = new User($row['id']);
            $returnableUser
                ->setEmail($row['email'])
                ->setName($row['name']);
            $returnableUser->password = $row['password'];

            return $returnableUser;
        }
    }

    public static function findOneByEmail($email)
    {
        $sql = "SELECT * FROM " . self::$table . " WHERE email = :email;";
        $deInlocuit = ['email' => $email];

        $list = self::$conn->prepare($sql);

        try {
            $result = $list->execute($deInlocuit);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        if ($list->rowCount() > 1) {
            die('nu se poate gasi un singur utilizator');
        } elseif ($list->rowCount() === 0) {
            return null;
        } else {
            $row = $list->fetch(PDO::FETCH_ASSOC);

            $returnableUser = new User($row['id']);
            $returnableUser
                ->setEmail($row['email'])
                ->setName($row['name']);
            $returnableUser->password = $row['password'];

            return $returnableUser;
        }
    }

    public function delete()
    {
        if ($this->getId() === null) {
            return true;
        } else {
            $sql = "DELETE FROM " . self::$table . " WHERE id = :id";
            $deInlocuit = ['id' => $this->getId()];

            $query = self::$conn->prepare($sql);
            try {
                $result = $query->execute($deInlocuit);
            } catch (PDOException $e) {
                die($e->getMessage());
            }

            if (!$result || $query->rowCount() === 0) {
                return false;
            }
        }

        $this->id = null;
        return true;
    }
}