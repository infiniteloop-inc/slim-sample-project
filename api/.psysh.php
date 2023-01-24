<?php

declare(strict_types=1);

return [
    'commands' => [
        new \Psy\Command\ParseCommand,
    ],

    'defaultIncludes' => [
        __DIR__ . '/bootstrap/psysh.php',
    ],

    'historySize' => 0,

    'useTabCompletion' => true,
];
