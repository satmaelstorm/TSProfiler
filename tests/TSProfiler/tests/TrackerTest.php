<?php
namespace satmaelstorm\TSProfiler\tests;

use satmaelstorm\TSProfiler\Tracker;

class TrackerTest extends \PHPUnit_Framework_TestCase
{
    public function testStartJob()
    {
        $tr = new Tracker("test");
        $r1 = $tr->startJob('job1');
        $r2 = $tr->startJob('job2');
        $r3 = $tr->startJob('job1');
        $this->assertEquals(0, $r1);
        $this->assertEquals(0, $r2);
        $this->assertEquals(1, $r3);
    }
    
    public function testStopJob()
    {
        $tr = new Tracker("test");
        $tr->startJob('job1');
        $tr->startJob('job2');
        $tr->startJob('job1');
        $this->assertTrue($tr->stopJob('job3')<0);
        $this->assertTrue($tr->stopJob('job1')>0);
        $this->assertTrue($tr->stopJob('job1')>0);
        $this->assertTrue($tr->stopJob('job1')<0);
        $this->assertTrue($tr->stopJob('job2')>0);
    }
}