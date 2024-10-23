<?php
namespace NYPL\Services\Model\CheckinRequest;

use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Model\LocalDateTime;
use NYPL\Starter\Model\ModelInterface\ReadInterface;
use NYPL\Starter\Model\ModelTrait\DBCreateTrait;
use NYPL\Starter\Model\ModelTrait\DBReadTrait;
use NYPL\Starter\Model\ModelTrait\DBUpdateTrait;

/**
 * @OA\Definition(title="CheckinRequest", type="object")
 *
 * @package NYPL\Services\Model\CheckinRequest
 */
class CheckinRequest extends NewCheckinRequest implements ReadInterface
{
    use DBCreateTrait, DBReadTrait, DBUpdateTrait;

    const REQUIRED_FIELDS = 'itemBarcode';

    /**
     * @OA\Property(example="229")
     * @var int
     */
    public $id;

    /**
     * @OA\Property(example=false)
     * @var bool
     */
    public $success = false;

    /**
     * @var bool
     */
    public $processed = true;

    /**
     * @OA\Property(example="2016-01-07T02:32:51Z", type="string")
     * @var LocalDateTime
     */
    public $createdDate;

    /**
     * @OA\Property(example="2016-01-07T02:32:51Z", type="string")
     * @var LocalDateTime
     */
    public $updatedDate;

    /**
     * @var string
     */
    public $checkinJobId;

    public function getSchema()
    {
        return
            [
                "name" => "CheckinRequest",
                "type" => "record",
                "fields" => [
                    ["name" => "id", "type" => "int"],
                    ["name" => "cancelRequestId", "type" => ["int", "null"]],
                    ["name" => "jobId", "type" => ["string", "null"]],
                    ["name" => "itemBarcode", "type" => "string"],
                    ["name" => "owningInstitutionId", "type" => ["string", "null"]],
                    ["name" => "success", "type" => "boolean"],
                    ["name" => "createdDate", "type" => ["string", "null"]],
                    ["name" => "updatedDate", "type" => ["string", "null"]],
                ]
            ];
    }

    /**
     * @return string
     */
    public function getSequenceId()
    {
        return 'checkin_request_id_seq';
    }

    /**
     * @return array
     */
    public function getIdFields()
    {
        return ['id'];
    }

    /**
     * @param int|string $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = (bool) $success;
    }

    /**
     * @return LocalDateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param LocalDateTime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @param string $createdDate
     *
     * @return LocalDateTime
     */
    public function translateCreatedDate($createdDate = '')
    {
        return new LocalDateTime(LocalDateTime::FORMAT_DATE_TIME_RFC, $createdDate);
    }

    /**
     * @return LocalDateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * @param LocalDateTime $updatedDate
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }

    /**
     * @param string $updatedDate
     *
     * @return LocalDateTime
     */
    public function translateUpdatedDate($updatedDate = '')
    {
        return new LocalDateTime(LocalDateTime::FORMAT_DATE_TIME_RFC, $updatedDate);
    }

    /**
     * @return mixed
     */
    public function getProcessed()
    {
        return $this->processed;
    }

    /**
     * @param mixed $processed
     */
    public function setProcessed($processed)
    {
        $this->processed = $processed;
    }

    /**
     * @return string
     */
    public function getCheckinJobId()
    {
        return $this->checkinJobId;
    }

    /**
     * @param string $checkinJobId
     */
    public function setCheckinJobId($checkinJobId)
    {
        $this->checkinJobId = $checkinJobId;
    }

    /**
     * @throws APIException
     */
    public function validatePostData()
    {
        $requiredFields = explode(',', self::REQUIRED_FIELDS);

        foreach ($requiredFields as $field) {
            if (!isset($this->$field)) {
                APILogger::addError(
                    'CheckinRequest object not instantiated. Bad request data sent.',
                    $this->getRawData()
                );
                throw new APIException("Checkin request is missing the {$field} element.", null, 0, null, 400);
            }
        }

        APILogger::addDebug('POST request payload validation passed.');
    }
}
