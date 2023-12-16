# Log Cleaner

Simple solution, made for easy old logs cleanup.

## Installation

Instalation is simple. Just get install composer dependency:
```
$ composer require pawelt/logcleaner
```

And inside project:

```injectablephp
<?php

use LogCleaner\LogCleanerContext;
use LogCleaner\Strategy\DTO\FileCleanerStrategyDTO;
use LogCleaner\Strategy\FileCleanerStrategy;

...

 // For example
 $logCleaner = new LogCleanerContext(new FileCleanerStrategy());

 $dto = new FileCleanerStrategyDTO();
 $dto->setPath('path/to/log');
 $dto->setTimePeriod(3);

 $logCleaner->clean($dto);
```

