<style>

pre {
    background: linear-gradient(90deg,#f8faff,rgba(255,255,255,0));
    color: #4762a7;
    padding: 1rem;
}

</style>

<?php

/**
 * Alpaca – Light and scalable PHP framework, for you.
 *
 * @package  Alpaca
 * @author   Miika Sikala <miikasikala96@gmail.com>
 */

require '../app/Framework/Bootstrap.php';

$app = new App\Framework\Bootstrap();
$app->run();
