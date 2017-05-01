<?php

namespace satmaelstorm\TSProfiler;

class ProfilerSingletone
{
    /** @var Profiler|null */
    private static $instance = null;
    
    /**
     * @param bool $createWithNoForceDisable
     * @return Profiler
     */
    public static function getInstance(bool $createWithNoForceDisable = true): Profiler
    {
        if (is_null(self::$instance)) {
            self::$instance = new Profiler($createWithNoForceDisable);
        }
        return self::$instance;
    }
}