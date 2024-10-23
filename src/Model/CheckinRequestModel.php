<?php
namespace NYPL\Services\Model;

use NYPL\Starter\Model;

/**
 * Class CheckinRequest
 *
 * @package \NYPL\Services\Model
 */
class CheckinRequestModel extends Model
{
    /**
     * @OA\Property(example="1234567890")
     * @var string
     */
    public $itemBarcode;

    /**
     * @OA\Property(example="NYPL")
     * @var string
     */
    public $owningInstitutionId;

    /**
     * @OA\Property(example="1234567890")
     * @var int
     */
    public $cancelRequestId;

    /**
     * @OA\Property(example="991873slx938")
     * @var string
     */
    public $jobId;

    /**
     * @return string
     */
    public function getItemBarcode()
    {
        return $this->itemBarcode;
    }

    /**
     * @param string $itemBarcode
     */
    public function setItemBarcode($itemBarcode)
    {
        $this->itemBarcode = $itemBarcode;
    }

    /**
     * @return string
     */
    public function getOwningInstitutionId()
    {
        return $this->owningInstitutionId;
    }

    /**
     * @param string $owningInstitutionId
     */
    public function setOwningInstitutionId($owningInstitutionId)
    {
        $this->owningInstitutionId = $owningInstitutionId;
    }

    /**
     * @return int
     */
    public function getCancelRequestId()
    {
        return $this->cancelRequestId;
    }

    /**
     * @param int $cancelRequestId
     */
    public function setCancelRequestId($cancelRequestId)
    {
        $this->cancelRequestId = (int)$cancelRequestId;
    }

    /**
     * @return string
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * @param string $jobId
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * @param array $properties
     */
    public function addExcludedProperties(array $properties)
    {
        $excluded = $this->getExcludeProperties();
        $excluded = array_merge($excluded, $properties);
        $this->setExcludeProperties($excluded);
    }

    /**
     * @param array $properties
     */
    public function removeExcludedProperties(array $properties)
    {
        $excluded = $this->getExcludeProperties();
        $excluded = array_diff($excluded, $properties);
        $this->setExcludeProperties($excluded);
    }
}
