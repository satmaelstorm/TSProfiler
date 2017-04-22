<?php
declare(strict_types = 1);

namespace satmaelstorm\TSProfiler\TSProfilerShutdown;


use satmaelstorm\TSProfiler\Tracker;

class CSVSaver implements ShutdownInterface
{
    /** @var string */
    private $path;
    /** @var string */
    private $delimeter;
    
    /**
     * CSVSaver constructor.
     * @param string $filePath
     */
    public function __construct(string $filePath, string $delimeter = "|")
    {
        $this->path = $filePath;
        $this->delimeter = $delimeter;
    }
    
    public function save(Tracker $tracker)
    {
        if (!$tracker->isEnable()) {
            return;
        }
        
        $fileName = $this->path . "TSProfiler_TRACKER_{$tracker->getName()}.csv";
        
        $newFile = !file_exists($fileName);
        $file = fopen($fileName, 'a');
        if ($newFile) {
            $this->putString($file, $this->makeHeader());
        }
        foreach ($tracker->getJobs() as $jName => $jData) {
            $this->putString($file, $this->makeRow($jName, $jData));
        }
        fclose($file);
    }
    
    private function makeHeader(): array
    {
        return ['Job Name', 'Min Time', 'Max Time', 'Avg Time', 'Call Count', 'Comment'];
    }
    
    private function makeRow(string $jobName, array $jobArray): array
    {
        $c = $sum = $max = $avg = 0;
        $min = PHP_INT_MAX;
        foreach ($jobArray['times'] as $t) {
            if ($min > $t['i']) {
                $min = $t['i'];
            }
            if ($max < $t['i']) {
                $max = $t['i'];
            }
            $sum += $t['i'];
            ++$c;
        }
        if ($c > 0) {
            $avg = $sum / $c;
        }
        $avg = str_replace(".", ",", sprintf('%.5f', $avg));
        $min = str_replace(".", ",", sprintf('%.5f', $min));
        $max = str_replace(".", ",", sprintf('%.5f', $max));
        return [$jobName, $min, $max, $avg, $jobArray['count'], $jobArray['comment']];
    }
    
    /**
     * @codeCoverageIgnore
     * @param resource $file
     * @param array    $row
     */
    private function putString($file, array $row)
    {
        fputcsv($file, $row, $this->delimeter);
    }
}