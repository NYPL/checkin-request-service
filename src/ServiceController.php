<?php
namespace NYPL\Services;

use NYPL\Starter\APILogger;
use NYPL\Starter\Config;
use NYPL\Starter\Controller;
use NYPL\Starter\Model\Response\ErrorResponse;
use Aura\Di\Container;

/**
 * Class ServiceController
 *
 * @package NYPL\Services
 */
class ServiceController extends Controller
{
    const READ_REQUEST_SCOPE = 'read:hold_request';
    const WRITE_REQUEST_SCOPE = 'write:hold_request';
    const GLOBAL_REQUEST_SCOPE = 'readwrite:hold_request';

    /**
     * @var bool
     */
    public $useJobService;

    /**
     * @var Container
     */
    public $container;

    /**
     * Controller constructor.
     *
     * @param \Slim\Container $container
     * @param int             $cacheSeconds
     */
    public function __construct(Container $container, int $cacheSeconds = 0)
    {
        $this->setUseJobService(Config::get('USE_JOB_SERVICE'));
        $this->setResponse($container->get('response'));
        $this->setRequest($container->get('request'));

        $this->addCacheHeader($cacheSeconds);

        $this->initializeContentType();

        $this->initializeIdentityHeader();

        parent::__construct($this->request, $this->response, $cacheSeconds);
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return boolean
     */
    public function isUseJobService(): bool
    {
        return $this->useJobService;
    }

    /**
     * @param boolean $useJobService
     */
    public function setUseJobService(bool $useJobService)
    {
        $this->useJobService = $useJobService;
    }

    /**
     * @return bool
     */
    public function hasReadRequestScope(): bool
    {
        return (bool) in_array(
            self::READ_REQUEST_SCOPE,
            (array) $this->getIdentityHeader()->getScopes()
        )
        || $this->hasGlobalRequestScope();
    }

    /**
     * @return bool
     */
    public function hasWriteRequestScope(): bool
    {
        return (bool) in_array(
            self::WRITE_REQUEST_SCOPE,
            (array) $this->getIdentityHeader()->getScopes()
        )
        || $this->hasGlobalRequestScope();
    }

    /**
     * @return bool
     */
    protected function hasGlobalRequestScope(): bool
    {
        return (bool) in_array(
            self::GLOBAL_REQUEST_SCOPE,
            (array) $this->getIdentityHeader()->getScopes()
        );
    }

    /**
     * @return bool
     */
    public function isRequestAuthorized()
    {
        APILogger::addDebug('Verifying valid OAuth scope.');

        if ($this->getRequest()->getMethod() === 'GET') {
            $hasScopeAccess = $this->hasReadRequestScope();
        } else {
            $hasScopeAccess = $this->hasWriteRequestScope();
        }

        return $hasScopeAccess;
    }

    /**
     * @param \Exception $exception
     * @return \Slim\Http\Response
     */
    public function invalidScopeResponse(\Exception $exception)
    {
        return $this->getJsonResponse(
            new ErrorResponse(
                '403',
                'invalid-scope',
                'Client does not have sufficient privileges. ' . $exception->getMessage()
            )
        )->withStatus(403);
    }

    /**
     * @param \Exception $exception
     * @return \Slim\Http\Response
     */
    public function invalidRequestResponse(\Exception $exception)
    {
        return $this->getJsonResponse(
            new ErrorResponse(
                '400',
                'invalid-request',
                'An invalid request was sent to the API. ' . $exception->getMessage()
            )
        )->withStatus(400);
    }
}
