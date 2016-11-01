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
    private $phones = [];
    private $mainPhone;

    const TABLE = "clients";
    const TABLE_PHONES = "phones";

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

    public function findPhones($id)
    {
        $table_phones = self::TABLE_PHONES;
        $sql = "SELECT * FROM {$table_phones} WHERE client_id = {$id}";
        $query = self::$conn->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPhones(){
        return $this->phones;
    }

    public function getMainPhone()
    {
        return $this->mainPhone;
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

    public function setPhones($phones)
    {
        $this->phones = $phones;
    }

    public function setMainPhone($phone)
    {
        $this->mainPhone = $phone;
    }

    //public function setMainPhone(){
    //    $table = self::TABLE;
    //    $sql = "SELECT * FROM {$table} WHERE active != 0";
//
    //    $query = self::$conn->prepare($sql);
    //    $query->execute();
    //}

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
        $table = self::TABLE;
        $table_phones = self::TABLE_PHONES;

        if ($this->id) {
            // gera um update
            $sql = "UPDATE {$table} SET first_name = :first_name, last_name = :last_name, birthday = :birthday WHERE id = :id";
            $query = self::$conn->prepare($sql);
            $query->bindParam(':first_name', $this->firstName, PDO::PARAM_STR);
            $query->bindParam(':last_name', $this->lastName, PDO::PARAM_STR);
            $query->bindParam(':birthday', $this->birthday, PDO::PARAM_STR);
            $query->bindParam(':id', $this->id, PDO::PARAM_INT);

            $query->execute();

            $sql = null;
            
            $sql = "UPDATE phones SET main = 0 WHERE client_id = {$this->id};
                    UPDATE phones SET main = 1 WHERE client_id = {$this->id} AND number = {$this->getMainPhone()};";


        } else {
            // gera um insert
            $sql = "INSERT INTO {$table} VALUES ('',:first_name,:last_name,:birthday,1)";
            $query = self::$conn->prepare($sql);
            $query->bindParam(':first_name', $this->firstName, PDO::PARAM_STR);
            $query->bindParam(':last_name', $this->lastName, PDO::PARAM_STR);
            $query->bindParam(':birthday', $this->birthday, PDO::PARAM_STR);
            $query->execute();
            $client_id = self::$conn->lastInsertId();
            $sql = null;
            
            foreach($this->phones as $phone){
                $number;
                if(isset($phone['number'])){
                    $number = $phone['number'];
                }else{
                    break;
                }
                $main = $phone['main'];
                $sql = "INSERT INTO {$table_phones} (number, client_id, main) VALUES (:phone, :client_id, :main);";
                $query = self::$conn->prepare($sql);
                $query->bindParam(':client_id', $client_id, PDO::PARAM_INT);
                $query->bindParam(':phone', $number, PDO::PARAM_STR);
                $query->bindParam(':main', $main, PDO::PARAM_INT);
                $query->execute();
            }

            
        }
    }
}
