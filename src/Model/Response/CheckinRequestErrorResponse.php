<?php
namespace NYPL\Services\Model\Response;

use NYPL\Starter\Model\Response\ErrorResponse;

/**
 * @OA\Definition(title="CheckinRequestErrorResponse", type="object")
 */
class CheckinRequestErrorResponse extends ErrorResponse
{
    public function __construct($statusCode = 500, $type = '', $message = '', $exception = null)
    {
        parent::__construct($statusCode, $type, $message, $exception);
    }
}
