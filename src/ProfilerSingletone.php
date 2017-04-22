<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 22.04.2017
 * Time: 13:59
 */

namespace satmaelstorm\TSProfiler;


class ProfilerSingletone
{
    private static $instance = null;
    
    public static function forceDisable()
    {
        if (!is_null(self::$instance)){
            
        }
    }
    
    public static function getInstance()
    {
        
    }
}