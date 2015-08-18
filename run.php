<?php

require __DIR__ . '/src/functions.php';
require __DIR__ . '/src/Scheduler.php';
require __DIR__ . '/src/Task.php';

require __DIR__ . '/recipe.php';

Skrasek\AsyncTaskTree\run();
