<?php

namespace Skrasek\AsyncTaskTree;


class TasksHolder
{
	public static $tasks = [];
	public static function getCallbacksByNames(array $tasks)
	{
		return array_map(function($name) {
			return self::$tasks[$name];
		}, $tasks);
	}
}


function run()
{
	$default = TasksHolder::$tasks['default'];

	$scheduler = new Scheduler();
	$scheduler->newTask($default);
	$scheduler->run();
}


function task($name, callable $callback)
{
	TasksHolder::$tasks[$name] = $callback;
}


/** @return \Closure */
function series(string ...$tasks)
{
	$callbacks = TasksHolder::getCallbacksByNames($tasks);

	return function() use ($callbacks) {
		foreach ($callbacks as $callback) {
			$generator = $callback();
			yield from $generator;
		}
	};
}


/** @return \Closure */
function parallel(string ...$tasks)
{
	$callbacks = TasksHolder::getCallbacksByNames($tasks);

	return function(Scheduler $scheduler) use ($callbacks) {
		foreach ($callbacks as $callback) {
			$scheduler->newTask($callback);
		}
		yield; // force Generator
	};
}
