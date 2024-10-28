<?php
namespace NYPL\Services\Test\Mocks;

use Aura\Di\Injection\InjectionFactory;
use Aura\Di\Resolver\Resolver;
use Aura\Di\Resolver\Reflector;
use NYPL\Services\ServiceContainer;
use NYPL\Starter\Service;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

/**
 * Class MockService
 *
 * @package NYPL\Services\Test\Mocks
 */
class MockService
{
    public static $mockContainer;

    /**
     * Set a concrete Container class to pass to controllers.
     *
     * TODO create true mock class.
     */
    public static function setMockContainer()
    {
        $reflector = new Reflector();
        $resolver = new Resolver($reflector);
        $injectionFactory = new InjectionFactory($resolver);
        self::$mockContainer = new ServiceContainer($injectionFactory);

        $_POST = [  
            'itemBarcode' => '1234567890123',
            'owningInstitutionId' => 'NYPL',
            'cancelRequestId' => '1234567890',
            'jobId' => '991873slx938'
        ];
        $_SERVER = [
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/',
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_CONTENT_TYPE' => 'application/json',
            'HTTP_X_NYPL_IDENTITY' => '{"token":"blah","identity":{"sub":null,"scope":"openid offline_access api read:hold_request"}}'
        ];
        $request = ServerRequest::fromGlobals();
        self::$mockContainer->set("request", $request);
        self::$mockContainer->set("response", new Response(200));

    }

    /**
     * @return mixed
     */
    public static function getMockContainer()
    {
        return self::$mockContainer;
    }
}
