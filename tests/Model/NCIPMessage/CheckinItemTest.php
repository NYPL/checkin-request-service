<?php
namespace NYPL\Test\Model\NCIPMessage;

use NYPL\Services\Model\CheckinRequest\CheckinRequest;
use NYPL\Services\Model\NCIPMessage\CheckinItem;
use PHPUnit\Framework\TestCase;

class CheckinItemTest extends TestCase
{
    public $checkinRequest;
    public $checkinItem;

    public function setUp()
    {
        parent::setUp();

        $data = json_decode(file_get_contents(__DIR__ . '/../../Stubs/validCheckinRequest.json'), true);

        $this->checkinRequest = new CheckinRequest($data);
        $this->checkinItem = new CheckinItem($this->checkinRequest);
    }

    /**
     * @covers NYPL\Services\Model\NCIPMessage\CheckinItem::buildMessage()
     * @covers NYPL\Services\Model\NCIPMessage\CheckinItem::initializeMessage()
     * @covers NYPL\Services\Model\NCIPMessage\CheckinItem::replacePatronBarcode()
     * @covers NYPL\Services\Model\NCIPMessage\CheckinItem::replaceItemBarcode()
     * @covers NYPL\Services\Model\NCIPMessage\CheckinItem::replaceDesiredDateDue()
     */
    public function testIfMessageIsInitialized()
    {
        self::assertInstanceOf('\NYPL\Services\Model\NCIPMessage\CheckinItem', $this->checkinItem);
        self::assertInstanceOf('\Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement', $this->checkinItem->message);
    }
}
