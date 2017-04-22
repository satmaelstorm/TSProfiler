<?php
declare(strict_types = 1);

namespace satmaelstorm\TSProfiler;

use satmaelstorm\TSProfiler\TSProfilerShutdown\ShutdownInterface;

class Profiler
{
    /** @var Tracker[] */
    private $trackers = [];
    /** @var bool */
    private $forceDisable;
    
    public function __construct(bool $forceDisable = false)
    {
        $this->forceDisable = $forceDisable;
        register_shutdown_function(function () {
            foreach ($this->trackers as $tracker) {
                $tracker->save();
            }
        });
    }
    
    /**
     * @return bool
     */
    public function getForceDisable(): bool
    {
        return $this->forceDisable;
    }
    
    /**
     * @param string                 $name
     * @param ShutdownInterface|null $saver
     * @param bool                   $enable
     * @return Tracker
     */
    public function addTracker(string $name, ShutdownInterface $saver = null, bool $enable = true): Tracker
    {
        if ($this->forceDisable) {
            $enable = false;
        }
        if (!isset($this->trackers[$name])) {
            $this->trackers[$name] = $this->createNewTracer($name, $saver, $enable);
        }
        return $this->trackers[$name];
    }
    
    /**
     * @param string $name
     * @return null|Tracker
     */
    public function getTracker(string $name)
    {
        if (!isset($this->trackers[$name])) {
            return null;
        }
        return $this->trackers[$name];
    }
    
    /**
     * @codeCoverageIgnore
     * @param string            $name
     * @param ShutdownInterface $saver
     * @param bool              $enable
     * @return Tracker
     */
    private function createNewTracer(string $name, ShutdownInterface $saver, bool $enable): Tracker
    {
        return new Tracker($name, $saver, $enable);
    }
}