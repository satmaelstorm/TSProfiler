<?php

namespace satmaelstorm\TSProfiler;

class ProfilerSingletone
{
    /** @var Profiler|null */
    private static $instance = null;
    
    /**
     * @param bool $createWithForceDisable
     * @return Profiler
     */
    public static function getInstance(bool $createWithForceDisable = false): Profiler
    {
        if (is_null(self::$instance)) {
            self::$instance = new Profiler($createWithForceDisable);
        }
        return self::$instance;
    }
}