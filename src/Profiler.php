<?php
declare(strict_types=1);

namespace satmaelstorm\TSProfiler;

class Profiler
{
    /** @var Tracker[]  */
    private $trackers = [];
    /** @var bool  */
    private $forceDisable;
    
    public function __construct(bool $forceDisable = false)
    {
        $this->forceDisable = $forceDisable;
        register_shutdown_function(function(){
            foreach ($this->trackers as $tracker){
                $tracker->save();
            }
        });
    }
    
    /**
     * @return bool
     */
    public function getForceDisable() : bool
    {
        return $this->forceDisable;
    }
    
    public function addTracker(string $name, bool $enable = true) : Tracker
    {
        
    }
}