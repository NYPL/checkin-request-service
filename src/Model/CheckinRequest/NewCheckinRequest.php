<?php
namespace NYPL\Services\Model\CheckinRequest;

use NYPL\Services\Model\CheckinRequestModel;
use NYPL\Starter\Model\ModelTrait\TranslateTrait;

/**
 * @OA\Definition(title="NewCheckinRequest", type="object")
 *
 * @package NYPL\Services\Model\CheckinRequest
 */
class NewCheckinRequest extends CheckinRequestModel
{
    use TranslateTrait;
}
