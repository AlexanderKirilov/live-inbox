<?php

require_once __DIR__ . '/../app/globals.php';
require_once FRAMEWORK_DIR . 'Bootstrap/autoload.php';
require_once FRAMEWORK_DIR . 'Bootstrap/run.php';


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