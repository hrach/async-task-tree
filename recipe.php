<?php

use function Skrasek\AsyncTaskTree\task;
use function Skrasek\AsyncTaskTree\parallel;
use function Skrasek\AsyncTaskTree\series;


task('css', function () {
	echo 1 . "\n";
	yield;
	echo 2 . "\n";
	yield;
	echo 3 . "\n";
	yield;
	echo 4 . "\n";
	yield;
	echo 5 . "\n";
	yield;
});


task('less', function () {
	echo 11 . "\n";
	yield;
	echo 12 . "\n";
	yield;
	echo 13 . "\n";
	yield;
});


// task('default', series('css', 'less'));
task('default', parallel('css', 'less'));
