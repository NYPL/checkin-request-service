<?php
namespace NYPL\Test\Model\CheckinRequest;

use NYPL\Services\Model\CheckinRequest\CheckinRequest;
use PHPUnit\Framework\TestCase;

class CheckinRequestTest extends TestCase
{
    public $checkinRequest;
    public $schema;

    public function setUp(): void
    {
        $this->checkinRequest = new CheckinRequest();
        $this->schema = $this->checkinRequest->getSchema();
    }

    /**
     * @covers NYPL\Services\Model\CheckinRequest\CheckinRequest::getSchema()
     */
    public function testIfSchemaHasValidKeys()
    {
        self::assertArrayHasKey('name', $this->schema);
        self::assertArrayHasKey('type', $this->schema);
        self::assertArrayHasKey('fields', $this->schema);
    }

    /**
     * @covers NYPL\Services\Model\CheckinRequest\CheckinRequest::getSchema()
     */
    public function testIfObjectContainsSchemaFields()
    {
        $fields = $this->schema['fields'];

        foreach ($fields as $field) {
            self::assertObjectHasProperty($field['name'], $this->checkinRequest);
        }
    }

    /**
     * @covers NYPL\Services\Model\CheckinRequest\CheckinRequest::setId()
     * @covers NYPL\Services\Model\CheckinRequest\CheckinRequest::getId()
     * @covers NYPL\Services\Model\CheckinRequest\CheckinRequest::setItemBarcode()
     * @covers NYPL\Services\Model\CheckinRequest\CheckinRequest::getItemBarcode()
     * @covers NYPL\Services\Model\CheckinRequest\CheckinRequest::setCancelRequestId()
     * @covers NYPL\Services\Model\CheckinRequest\CheckinRequest::getCancelRequestId()
     * @covers NYPL\Services\Model\CheckinRequest\CheckinRequest::setJobId()
     * @covers NYPL\Services\Model\CheckinRequest\CheckinRequest::setOwningInstitutionId()
     * @covers NYPL\Services\Model\CheckinRequest\CheckinRequest::validatePostData()
     */
    public function testIfPostDataIsValid()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/../../Stubs/validCheckinRequest.json'), true);
        $newRequest = new CheckinRequest($data);

        self::assertInstanceOf('\NYPL\Services\Model\CheckinRequest\CheckinRequest', $newRequest);
    }

    /**
     * @expectedException \NYPL\Starter\APIException
     * @covers NYPL\Services\Model\CheckinRequest\CheckinRequest::validatePostData()
     */
    public function testIfInvalidPostDataThrowsException()
    {
        $this->expectException('\NYPL\Starter\APIException');

        $this->checkinRequest->setJobId('abcdefg');

        $this->checkinRequest->validatePostData();
    }
}
