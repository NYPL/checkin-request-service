<?php
namespace NYPL\Services;

use NYPL\Starter\DefaultContainer;

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
        ContainerInterface $delegateContainer)
    {
        parent::__construct($injectionFactory,  $delegateContainer);
        $this["settings"]["displayErrorDetails"] = true;
    }
}
