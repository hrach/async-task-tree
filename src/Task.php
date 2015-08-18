<?php

namespace Skrasek\AsyncTaskTree;


class Task
{
	protected $taskId;

	protected $coroutine;

	protected $sendValue = NULL;

	protected $beforeFirstYield = TRUE;


	public function __construct($taskId, \Generator $coroutine)
	{
		$this->taskId = $taskId;
		$this->coroutine = $coroutine;
	}


	public function getTaskId()
	{
		return $this->taskId;
	}


	public function setSendValue($sendValue)
	{
		$this->sendValue = $sendValue;
	}


	protected $exception = NULL;


	public function setException($exception)
	{
		$this->exception = $exception;
	}


	public function run()
	{
		if ($this->beforeFirstYield) {
			$this->beforeFirstYield = FALSE;
			return $this->coroutine->current();

		} elseif ($this->exception) {
			$retval = $this->coroutine->throw($this->exception);
			$this->exception = NULL;
			return $retval;

		} else {
			$retval = $this->coroutine->send($this->sendValue);
			$this->sendValue = NULL;
			return $retval;
		}
	}


	public function isFinished()
	{
		return !$this->coroutine->valid();
	}

}
