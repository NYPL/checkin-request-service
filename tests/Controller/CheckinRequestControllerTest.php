<?php
namespace NYPL\Test\Controller;

use NYPL\Services\Controller\CheckinRequestController;
use NYPL\Services\Test\Mocks\MockConfig;
use NYPL\Services\Test\Mocks\MockService;
use PHPUnit\Framework\TestCase;

class CheckinRequestControllerTest extends TestCase
{
    public $fakeCheckinController;

    public function setUp()
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
                parent::__construct($container, $cacheSeconds);
            }

            public function processCheckinRequest()
            {
                return parent::processCheckinRequest();
            }

        };
    }

    /**
     * @covers NYPL\Services\Controller\CheckinRequestController::processCheckinRequest()
     */
    public function testCreatesCheckOutModelFromRequest()
    {
        $controller = $this->fakeCheckinController;

        $response = $controller->processCheckinRequest();

        self::assertInstanceOf('Slim\Http\Response', $response);
    }

    /**
     * @covers NYPL\Services\Controller\CheckinRequestController::processCheckinRequest()
     */
    public function testMisconfigurationThrowsException()
    {
        $controller = $this->fakeCheckinController;

        $response = $controller->processCheckinRequest();

        self::assertSame(400, $response->getStatusCode());
    }
}
