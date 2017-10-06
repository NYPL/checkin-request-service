<?php
namespace NYPL\Services;

/**
 * Class NCIPResponse
 *
 * @package NYPL\Services
 */
abstract class NCIPResponse
{
    /**
     * @var \SimpleXMLElement
     */
    public $xml;

    /**
     * @return \SimpleXMLElement
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * @param \SimpleXMLElement $xml
     */
    public function setXml($xml)
    {
        $this->xml = $xml;
    }

    /**
     * @return string
     */
    abstract public function parse();

    /**
     * @return int
     */
    abstract public function getStatusCode();
}
