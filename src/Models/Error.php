<?php

namespace Setra\Models;

class Error
{
    private $errors = [];

    public function addError($error)
    {
        array_push($this->errors, $error);
    }

    public function modelIsValid(){
        return (sizeof($this->errors) == 0) ? true : false;
    }

    public function getErrors(){
        return $this->errors;
    }

    public function displayAll(){
        foreach($this->errors as $error){
            print($error."\n");
        }
    }
    
}
