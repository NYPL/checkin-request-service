<?php
require __DIR__ . '/vendor/autoload.php';

use NYPL\Services\Controller\CheckinRequestController;
use NYPL\Services\ServiceContainer;
use NYPL\Starter\SwaggerGenerator;
use NYPL\Starter\Service;
use NYPL\Starter\Config;
use NYPL\Starter\ErrorHandler;
use Aura\Di\Injection\InjectionFactory;
use Aura\Di\Resolver\Resolver;
use Aura\Di\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

try {
    Config::initialize(__DIR__);

    $service = new Service();
    $service->addBodyParsingMiddleware();

    $service->get("/docs/checkin-requests", function (Request $request, Response $response) {
        return SwaggerGenerator::generate(
            [__DIR__ . "/src", __DIR__ . "/vendor/nypl/microservice-starter/src"],
            $response
        );
    });

    $service->get("/docs/checkin-requests-sync", function (Request $request, Response $response) {
        return SwaggerGenerator::generate(
            [__DIR__ . "/src", __DIR__ . "/vendor/nypl/microservice-starter/src"],
            $response
        );
    });

    $service->post("/api/v0.1/checkin-requests", function (Request $request, Response $response) {
        $this->set("response", $response);
        $this->set("request", $request);
        $controller = new CheckinRequestController($this);
        return $controller->createCheckinRequest();
    });

    $service->post("/api/v0.1/checkin-requests-sync", function (Request $request, Response $response) {
        $this->set("response", $response);
        $this->set("request", $request);
        $controller = new CheckinRequestController($this);
        return $controller->createCheckinRequest();
    });

    $service->run();
} catch (Exception $exception) {
    ErrorHandler::processShutdownError($exception->getMessage(), $exception);
}
