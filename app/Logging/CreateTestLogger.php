<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\TestHandler;

class CreateTestLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return Logger
     */
    public function __invoke(array $config)
    {
        $monolog = new Logger('test');
        $monolog->pushHandler(new TestHandler());

        return $monolog;
    }
}
