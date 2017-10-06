<?php
namespace NYPL\Services\Model\NCIPResponse;

use NYPL\Services\NCIPResponse;

/**
 * Class CheckinItemErrorResponse
 *
 * @package NYPL\Services\Model\NCIPResponse
 */
class CheckinItemErrorResponse extends NCIPResponse
{
    /**
     * @var int
     */
    public $statusCode = 208;

    /**
     * @var string
     */
    public $problem = '';

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = (int)$statusCode;
    }

    /**
     * @return string
     */
    public function getProblem()
    {
        return $this->problem;
    }

    /**
     * @param string $problem
     */
    public function setProblem($problem)
    {
        $this->problem = $problem;
    }

    /**
     * CheckinItemErrorResponse constructor.
     *
     * @param \SimpleXMLElement $xml
     */
    public function __construct(\SimpleXMLElement $xml)
    {
        $this->setXml($xml);
    }

    /**
     * @return string
     */
    public function parse()
    {
        return $this->getXml()->asXML();
    }
}
