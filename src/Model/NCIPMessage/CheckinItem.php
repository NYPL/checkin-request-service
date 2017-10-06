<?php
namespace NYPL\Services\Model\NCIPMessage;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;
use NYPL\Services\Model\CheckinRequest\CheckinRequest;
use NYPL\Services\NCIPMessage;

/**
 * Class CheckinItem
 *
 * @package NYPL\Services\Model\NCIPMessage
 */
class CheckinItem extends NCIPMessage
{
    const XML_NAMESPACE = 'http://www.niso.org/2008/ncip';
    const XML_PREFIX = 'ci';
    const XML_NODE = 'CheckInItem';

    /**
     * @var string
     */
    public $itemBarcode = '';

    /**
     * @var string|QuiteSimpleXMLElement
     */
    public $message = '';

    /**
     * @var string
     */
    public $xml = '';

    /**
     * CheckinItem constructor.
     *
     * @param CheckinRequest $checkinRequest
     */
    public function __construct(CheckinRequest $checkinRequest)
    {
        if ($checkinRequest->getItemBarcode()) {
            $this->setItemBarcode($checkinRequest->getItemBarcode());
        }

        $this->buildMessage();
    }

    /**
     * @return string
     */
    public function getXml()
    {
        if (!$this->xml) {
            $this->setXml(file_get_contents(__DIR__ . '/XML/CheckInItem.xml'));
        }

        return $this->xml;
    }

    /**
     * @param string $xml
     */
    public function setXml($xml)
    {
        $this->xml = $xml;
    }

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
     * @return QuiteSimpleXMLElement
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param QuiteSimpleXMLElement $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function messageToString()
    {
        return $this->getMessage()->asXML();
    }

    /**
     * Retrieve the default NCIP message provided by third-party client.
     */
    protected function initializeMessage()
    {
        $xmlElem = new QuiteSimpleXMLElement($this->getXml());
        $xmlElem->registerXPathNamespace(self::XML_PREFIX, self::XML_NAMESPACE);

        $this->setMessage($xmlElem);
    }

    /**
     * Substitute default XML value with value sent in the checkin request.
     */
    protected function replaceItemBarcode()
    {
        $itemXml = $this->getMessage()->first(self::XML_PREFIX . ':' . self::XML_NODE . '/ci:ItemId/ci:ItemIdentifierValue');
        $itemXml->setValue($this->getItemBarcode());
    }

    /**
     * @return $this
     */
    protected function buildMessage()
    {
        $this->initializeMessage();
        $this->replaceItemBarcode();

        return $this;
    }
}
