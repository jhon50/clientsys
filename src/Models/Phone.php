<?php

namespace Setra\Models;

class Phone
{
    private static $conn;

    private $id;
    private $number;
    private $clientId;
    private $isMain;

    const TABLE = "phones";

    public function __construct($number, $clientId, $isMain)
    {
        $this->number = $number;
        $this->clientId = $clientId;
        $this->isMain = $isMain;
    }
}
