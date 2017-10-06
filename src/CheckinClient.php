<?php
namespace NYPL\Services;

use NYPL\Services\Model\CheckinRequest\CheckinRequest;
use NYPL\Services\Model\NCIPMessage\CheckinItem;
use NYPL\Services\Model\NCIPResponse\CheckinItemResponse;
use NYPL\Starter\APIClient;
use NYPL\Starter\APILogger;

/**
 * Class CheckinClient
 *
 * @package NYPL\Services
 */
class CheckinClient extends APIClient
{
    /**
     * @var CheckinItem
     */
    public $checkinItem;

    /**
     * @return CheckinItem
     */
    public function getCheckinItem()
    {
        return $this->checkinItem;
    }

    /**
     * @param mixed $checkinItem
     */
    public function setCheckinItem($checkinItem)
    {
        $this->checkinItem = $checkinItem;
    }

    /**
     * @return bool
     */
    protected function isRequiresAuth()
    {
        return false;
    }

    /**
     * @param CheckinRequest $checkinRequest
     * @return NCIPResponse
     */
    public function buildCheckinRequest(CheckinRequest $checkinRequest)
    {
        APILogger::addDebug('Request model', [$checkinRequest]);

        if (!$this->getCheckinItem()) {
            $this->setCheckinItem(new CheckinItem($checkinRequest));
        }

        APILogger::addDebug('Checkin Obj', $this->getCheckinItem()->getMessage()->asXML());

        $ncipResponse = $this->sendCheckinRequest();

        return $ncipResponse;
    }

    /**
     * @return CheckinItemResponse
     */
    protected function sendCheckinRequest()
    {
        return NCIPClient::sendNCIPMessage($this->getCheckinItem());
    }
}
