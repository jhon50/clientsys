<?php

namespace Setra\Models;

use PDO;

class Client
{
    private static $conn;

    private $id;
    private $firstName;
    private $lastName;
    private $birthday;
    private $active;

    const TABLE = "clients";

    public function __construct($id = null)
    {
        if ($id) {

            $client = $this->findById($id);

            $this->id = $id;
            $this->firstName = $client->first_name;
            $this->lastName = $client->last_name;
            $this->birthday = $client->birthday;
            $this->active = $client->active;
        }
    }

    public static function setConnection(PDO $conn)
    {
        self::$conn = $conn;
    }

    //GET
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getFullName()
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function getBirthday()
    {
        return $this->birthday;
    }

    public function getStatus()
    {
        return $this->active ? true : false;
    }

    //SET
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    public function setStatus($status)
    {
        if ($status) {
            $this->active = 1;
        } else {
            $this->active = 0;
        }

    }

    //DB METHODS
    public function findAll()
    {
        $table = self::TABLE;
        $sql = "SELECT * FROM {$table} WHERE active != 0";

        $query = self::$conn->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {

        $table = self::TABLE;
        $sql = "SELECT first_name, last_name, birthday, active FROM {$table} WHERE id = :id";

        $query = self::$conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function delete()
    {
        $table = self::TABLE;
        $sql = "UPDATE {$table} SET active = 0 WHERE id = :id";
        $query = self::$conn->prepare($sql);
        $query->bindParam(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
    }

    public function save()
    {
        if ($this->id) {
            // gera um update
            $table = self::TABLE;
            $sql = "UPDATE {$table} SET first_name = :first_name, last_name = :last_name, birthday = :birthday WHERE id = :id";
            $query = self::$conn->prepare($sql);
            $query->bindParam(':first_name', $this->firstName, PDO::PARAM_STR);
            $query->bindParam(':last_name', $this->lastName, PDO::PARAM_STR);
            $query->bindParam(':birthday', $this->birthday, PDO::PARAM_STR);
            $query->bindParam(':id', $this->id, PDO::PARAM_INT);

            $query->execute();

        } else {
            // gera um insert
            $table = self::TABLE;
            $sql = "INSERT INTO {$table} VALUES ('','{$this->getFirstName()}','{$this->getLastName()}','{$this->getBirthday()}','1')";
            $query = self::$conn->prepare($sql);
            $query->execute();
        }
    }
}
