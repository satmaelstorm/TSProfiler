# TSProfiler

Tiny and Simple PHP-runtime Profiler

This component allows you to profile the code directly in the production environment.

You can use it as a Singleton:
```php
$profiler = ProfilerSingletone::getInstance();
```
Also, if you want disable profiling at this time, you can use:
```php
$profiler = ProfilerSingletone::getInstance(false);
```
So, if you don't want use Singleton, you can use:
```php
$profiler = new Profiler();
```
or
```php
$profiler = new Profiler(false);
``` 
when you don't need profiler at this time

This case very useful, when you want to use TSProfiler as Symfony service,
or in another framework, which will ensure the uniqueness of
the existence of a class instance.

For profiling, trackers are created using the addTracker method. Each tracker can
store data in its own way. The profiling data will save the class that implements
the ShutdownInterface interface. You can write any plug-in - save with sql-server, file,
Monolog or some other way.
```php
$tracker = $profiler->addTracker($name, $saver);
```
If you want disable this tracker at this time:
```php
$tracker = $profiler->addTracker($name, $saver, false);
```
Example with CSVSaver class:
```php
$tracker = $profiler->addTracker(
    'csvTracker',
    new \satmaelstorm\TSProfiler\TSProfilerShutdown\CSVSaver('\tmp'); 
                                );
```
After creating the tracker, you wrap the profiled lines using the startJob
and stopJob methods of the tracker.
```php
$tracker->startJob('jobName');
//profiled code
$tracker->stopJob('jobName');
```


