<?php

require_once __DIR__ . '/../src/globals.php';
require_once FRAMEWORK_DIR . 'Bootstrap/autoload.php';
require_once FRAMEWORK_DIR . 'Bootstrap/run.php';

$pdo = new PDO("mysql:dbname={$_ENV['DB_NAME']};host=" . getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'));

$actions = [
    [
        'name' => 'Go to Raiffaisen',
        'description' => 'Go to Raiffaisen and get debit card',
    ],
    [
        'name' => 'Go to Raiffaisen',
        'description' => 'Go to Raiffaisen and get debit card',
    ],
    [
        'name' => 'Go to Raiffaisen',
        'description' => 'Go to Raiffaisen and get debit card',
    ],
    [
        'name' => 'Go to Raiffaisen',
        'description' => 'Go to Raiffaisen and get debit card',
    ],
    [
        'name' => 'Go to Raiffaisen',
        'description' => 'Go to Raiffaisen and get debit card',
    ]
];
?>
<h1>HELLO WORLD</h1>
<ul>
    <?php foreach ($actions as $action) :?>
        <li><?php echo $action['name']?></li>
    <?php endforeach; ?>
</ul>