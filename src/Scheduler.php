<?php

namespace Skrasek\AsyncTaskTree;

use SplQueue;


class Scheduler
{
	protected $maxTaskId = 0;
	protected $taskMap = []; // taskId => task
	protected $taskQueue;


	public function __construct()
	{
		$this->taskQueue = new SplQueue();
	}


	public function newTask(callable $callback)
	{
		$coroutine = $callback($this);
		$tid = ++$this->maxTaskId;
		$task = new Task($tid, $coroutine);
		$this->taskMap[$tid] = $task;
		$this->schedule($task);

		return $tid;
	}


	public function schedule(Task $task)
	{
		$this->taskQueue->enqueue($task);
	}


	public function run()
	{
		while (!$this->taskQueue->isEmpty()) {
			$task = $this->taskQueue->dequeue();
			$task->run();

			if ($task->isFinished()) {
				unset($this->taskMap[$task->getTaskId()]);
			} else {
				$this->schedule($task);
			}
		}
	}
}
