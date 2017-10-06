<?php
namespace NYPL\Services;

/**
 * Class NCIPMessage
 *
 * @package NYPL\Services
 */
abstract class NCIPMessage
{
    /**
     * @var string
     */
    public $message = '';

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    abstract function messageToString();

    /**
     * @param \SimpleXMLElement  $xml
     * @param string             $node
     * @param string             $value
     * @param string             $namespace
     * @return \SimpleXMLElement
     */
    public function addElement(\SimpleXMLElement $xml, $node = '', $value = '', $namespace = '')
    {
        $xml->addChild($node, $value, $namespace);

        return $xml;
    }
}
