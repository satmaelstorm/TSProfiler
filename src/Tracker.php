<?php
declare(strict_types = 1);

namespace satmaelstorm\TSProfiler;

use satmaelstorm\TSProfiler\TSProfilerShutdown\ShutdownInterface;

class Tracker
{
    /** @var bool */
    private $enable;
    /** @var array */
    private $jobs = [];
    /** @var ShutdownInterface|null */
    private $saver = null;
    /** @var string */
    private $name;
    
    /**
     * Tracker constructor.
     * @param string                 $name
     * @param ShutdownInterface|null $saver
     * @param bool                   $enable
     */
    public function __construct(string $name, ShutdownInterface $saver = null, bool $enable = true)
    {
        $this->saver = $saver;
        $this->enable = $enable;
        $this->name = $name;
    }
    
    /**
     * @param string $name
     * @param string $comment
     * @return int
     */
    public function startJob(string $name, string $comment = ''): int
    {
        if (!$this->enable) {
            return -1;
        }
        if (!isset($this->jobs[$name])) {
            $this->jobs[$name] = $this->getNewJobArray($comment);
        }
        $runId = $this->jobs[$name]['count'];
        ++$this->jobs[$name]['count'];
        $this->jobs[$name]['times'][$runId] = ['s' => 0, 'e' => 0, 'i' => 0];
        //As close as possible to the end of the function
        $this->jobs[$name]['times'][$runId]['s'] = microtime(true);
        return $runId;
    }
    
    /**
     * @param string   $name
     * @param int|null $runId
     * @return float
     */
    public function stopJob(string $name, int $runId = null): float
    {
        $t = microtime(true);
        if (!$this->enable) {
            return -1.0;
        }
        if (!isset($this->jobs[$name])) {
            return -1.0;
        }
        if (is_null($runId)) {
            $runId = $this->jobs[$name]['count'] - 1;
        }
        if (!isset($this->jobs[$name]['times'][$runId])) {
            return -1.0;
        }
        if (!empty($this->jobs[$name]['times'][$runId]['e'])) {
            return -1.0;
        }
        $this->jobs[$name]['times'][$runId]['e'] = $t;
        $this->jobs[$name]['times'][$runId]['i'] = $this->jobs[$name]['times'][$runId]['e']
            - $this->jobs[$name]['times'][$runId]['s'];
        return $this->jobs[$name]['times'][$runId]['i'];
        
    }
    
    /**
     * @param string $comment
     * @return array
     */
    private function getNewJobArray(string $comment): array
    {
        return [
            'count' => 0,
            'times' => [],
            'comment' => $comment
        ];
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }
    
    /**
     * @param bool $enable
     * @return Tracker
     */
    public function setEnable(bool $enable): Tracker
    {
        $this->enable = $enable;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getJobs(): array
    {
        return $this->jobs;
    }
    
    /**
     * @return null|ShutdownInterface
     */
    public function getSaver()
    {
        return $this->saver;
    }
    
    /**
     * @param null|ShutdownInterface $saver
     * @return Tracker
     */
    public function setSaver($saver)
    {
        $this->saver = $saver;
        return $this;
    }
    
    /**
     * Save tracer information
     * Shoud be use by register_shutdown_function ONLY!
     */
    public function save()
    {
        if ($this->enable && !is_null($this->saver)) {
            $this->saver->save($this);
        }
    }
    
}