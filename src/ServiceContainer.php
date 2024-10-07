<?php
namespace NYPL\Services;

use NYPL\Starter\DefaultContainer;
use Aura\Di\Injection\InjectionFactory;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceContainer
 *
 * @package NYPL\Services
 */
class ServiceContainer extends DefaultContainer
{
    /**
     * ServiceContainer constructor.
     */
    public function __construct(
        InjectionFactory $injectionFactory,
        ContainerInterface $delegateContainer = null)
    {
        parent::__construct($injectionFactory,  $delegateContainer);
        $this["settings"]["displayErrorDetails"] = true;
    }
}
