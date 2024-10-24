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

        $headers = [
            'X-NYPL-Identity' => 
            '{"token":"blah","identity":{"sub":null,"scope":"openid offline_access api read:hold_request"}}'
        ];
        $body = '{"itemBarcode": "1234567890123", "owningInstitutionId": "NYPL", "cancelRequestId": "1234567890", "jobId": "991873slx938"}';
        self::$mockContainer->set("request", new ServerRequest('POST', '/', $headers, $body));
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
