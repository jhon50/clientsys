<?php

namespace Setra\Helpers;

class Date 
{
    public static function formatToView($date)
    {
        return date('d/m/Y',strtotime($date));
    }

    public static function formatToDb($date)
    {
        return date('Y-m-d',strtotime($date));
    }
}

