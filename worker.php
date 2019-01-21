<?php
declare(strict_types=1);

ini_set('display_errors', 'stderr');
require 'vendor/autoload.php';

$container = new \Slim\Container();
$app = new Slim\App($container);

$app->get('/hello/{name}', function ($request, $response, $args) {
    $response->getBody()->write('Hello, ' . $args['name']);
    return $response;
});

$relay = new Spiral\Goridge\StreamRelay(STDIN, STDOUT);
$psr7 = new Spiral\RoadRunner\PSR7Client(new Spiral\RoadRunner\Worker($relay));

while ($request = $psr7->acceptRequest()) {
    try {
        $container['request'] = $request;
        $container['response'] = new \Zend\Diactoros\Response();
        $response = $app->run(true);
        $psr7->respond($response);
    } catch (\Throwable $e) {
        $psr7->getWorker()->error((string)$e);
    }
}
