<?php
namespace NYPL\Services\Model\Response;

use NYPL\Services\Model\CheckinRequest\CheckinRequest;
use NYPL\Starter\Model\Response\SuccessResponse;

/**
 * @SWG\Definition(title="CheckinRequestResponse", type="object")
 */
class CheckinRequestResponse extends SuccessResponse
{
    /**
     * @SWG\Property
     * @var CheckinRequest
     */
    public $data;
}
