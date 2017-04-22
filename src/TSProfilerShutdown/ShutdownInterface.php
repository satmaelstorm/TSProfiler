<?php

namespace satmaelstorm\TSProfiler\TSProfilerShutdown;

use satmaelstorm\TSProfiler\Tracker;

interface ShutdownInterface
{
    public function save(Tracker $tracer);
}