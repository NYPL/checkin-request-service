<?php
namespace NYPL\Services\Model\NCIPResponse;

use NYPL\Services\NCIPResponse;

/**
 * Class CheckinItemResponse
 *
 * @package NYPL\Services\Model\NCIPResponse
 */
class CheckinItemResponse extends NCIPResponse
{
    /**
     * @var int
     */
    public $statusCode = 202;

    /**
     * CheckinItemResponse constructor.
     *
     * @param \SimpleXMLElement $xml
     */
    public function __construct(\SimpleXMLElement $xml)
    {
        $this->setXml($xml);
    }

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
    public function parse()
    {
        return $this->getXml()->asXML();
    }
}
