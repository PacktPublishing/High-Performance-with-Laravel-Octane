<?php

include 'vendor/autoload.php';

use Nyholm\Psr7;
use Spiral\RoadRunner;

$worker = RoadRunner\Worker::create();
$psrFactory = new Psr7\Factory\Psr17Factory();
$worker = new RoadRunner\Http\PSR7Worker($worker, $psrFactory, $psrFactory, $psrFactory);
$id = uniqid('', true);

echo "STARTING ${id}";

while ($req = $worker->waitRequest()) {
    try {
        $rsp = new Psr7\Response();
        $rsp->getBody()->write("Hello ${id}");
        echo "RESPONSE SENT from ${id}";
        $worker->respond($rsp);
    } catch (\Throwable $e) {
        $worker->getWorker()->error((string) $e);
        echo 'ERROR '.$e->getMessage();
    }
}
