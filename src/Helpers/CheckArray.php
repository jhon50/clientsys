<?php

namespace Setra\Helpers;

class CheckArray
{
    public static function hasOnlyOneValue($array)
    {
        $notNull = 0;
        foreach($array as $item){
            if($item != null){
                $notNull ++;
            }
        }
        return $notNull > 1 ? false : true;
    }

    public static function formatToDb($date)
    {
        return date('Y-m-d',strtotime($date));
    }
}

