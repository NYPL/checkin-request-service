<?php
namespace NYPL\Services\Model\Response;

use NYPL\Services\Model\CheckinRequest\CheckinRequest;
use NYPL\Starter\Model\Response\SuccessResponse;

/**
 * @OA\Definition(title="CheckinRequestResponse", type="object")
 */
class CheckinRequestResponse extends SuccessResponse
{
    /**
     * @OA\Property
     * @var CheckinRequest
     */
    public $data;
}
