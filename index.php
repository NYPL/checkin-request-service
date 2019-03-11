<?php
require __DIR__ . '/vendor/autoload.php';

use NYPL\Services\Controller\CheckinRequestController;
use NYPL\Services\ServiceContainer;
use NYPL\Services\Swagger;
use NYPL\Starter\Service;
use NYPL\Starter\Config;
use NYPL\Starter\ErrorHandler;

try {
    Config::initialize(__DIR__);

    $container = new ServiceContainer();

    $service = new Service($container);

    $service->get('/docs/checkin-requests', Swagger::class);

    $service->get('/docs/checkin-requests-sync', Swagger::class)

    $service->post('/api/v0.1/checkin-requests', CheckinRequestController::class . ':createCheckinRequest');

    $service->post('/api/v0.1/checkin-requests-sync', CheckinRequestController::class . ':createCheckinRequest');

    $service->run();
} catch (Exception $exception) {
    ErrorHandler::processShutdownError($exception->getMessage(), $exception);
}
