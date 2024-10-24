<?php
namespace NYPL\Test\Controller;

use NYPL\Services\Controller\CheckinRequestController;
use NYPL\Services\Test\Mocks\MockConfig;
use NYPL\Services\Test\Mocks\MockService;
use PHPUnit\Framework\TestCase;

class CheckinRequestControllerTest extends TestCase
{
    public $fakeCheckinController;

    public function setUp(): void
    {
        parent::setUp();
        MockConfig::initialize(__DIR__ . '/../../');
        MockService::setMockContainer();
        $this->mockContainer = MockService::getMockContainer();

        $this->fakeCheckinController = new class(MockService::getMockContainer(), 0) extends CheckinRequestController {

            public $container;
            public $cacheSeconds;

            public function __construct(\Slim\Container $container, $cacheSeconds)
            {
                $this->setUseJobService(1);
                parent::__construct($container, $cacheSeconds);
            }

            public function createCheckinRequest()
            {
                return parent::createCheckinRequest();
            }

        };
    }

    /**
     * @covers NYPL\Services\Controller\CheckinRequestController::createCheckinRequest()
     */
    public function testCreatesCheckOutModelFromRequest()
    {
        $controller = $this->fakeCheckinController;

        $response = $controller->createCheckinRequest();

        self::assertInstanceOf('GuzzleHttp\Psr7\Response', $response);
    }

    /**
     * @covers NYPL\Services\Controller\CheckinRequestController::createCheckinRequest()
     */
    public function testMisconfigurationThrowsException()
    {
        $controller = $this->fakeCheckinController;

        $response = $controller->createCheckinRequest();

        self::assertSame(500, $response->getStatusCode());
    }
}
