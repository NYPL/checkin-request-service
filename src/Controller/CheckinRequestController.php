<?php
namespace NYPL\Services\Controller;

use NYPL\Services\CancelRequestLogger;
use NYPL\Services\CheckinClient;
use NYPL\Services\JobService;
use NYPL\Services\Model\CheckinRequest\CheckinRequest;
use NYPL\Services\Model\NCIPResponse\CheckinItemErrorResponse;
use NYPL\Services\Model\Response\CheckinRequestErrorResponse;
use NYPL\Services\Model\Response\CheckinRequestResponse;
use NYPL\Services\ServiceController;
use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Filter;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class CheckinRequestController
 *
 * @package NYPL\Services\Controller
 */
class CheckinRequestController extends ServiceController
{
    /**
     * @SWG\Post(
     *     path="/v0.1/checkin-requests",
     *     summary="Process a checkin request",
     *     tags={"checkin-requests"},
     *     operationId="processCheckinRequest",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="NewCheckinRequest",
     *         in="body",
     *         description="Request object based on the included data model",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/NewCheckinRequest")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(ref="#/definitions/CheckinRequestResponse")
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not found",
     *         @SWG\Schema(ref="#/definitions/CheckinRequestErrorResponse")
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Generic server error",
     *         @SWG\Schema(ref="#/definitions/CheckinRequestErrorResponse")
     *     ),
     *     security={
     *         {
     *             "api_auth": {"openid offline_access api write:hold_request readwrite:hold_request"}
     *         }
     *     }
     * )
     *
     * @throws APIException
     * @return Response
     */
    public function createCheckinRequest()
    {
        try {

            $data = $this->getRequest()->getParsedBody();
            $checkinRequest = new CheckinRequest($data);
            // Exclude checkinJobId and processed values used for non-cancellation responses.
            $checkinRequest->addExcludedProperties(['checkinJobId', 'processed']);

            APILogger::addDebug('POST request sent.', $data);

            $this->initiateCheckinRequest($checkinRequest);

            // Assume success unless an error response is returned.
            $successFlag = true;
            $checkinStatus = 200;

            // Send the request to the NCIP client.
            $checkinResponse = $this->sendCircOperation($checkinRequest);

            if ($checkinResponse instanceof CheckinRequestErrorResponse) {
                $successFlag = false;
                $checkinStatus = $checkinResponse->getStatusCode();
            }

            $this->updateCheckinRequest($checkinRequest, $successFlag);

            return $this->getResponse()->withJson($checkinResponse)->withStatus($checkinStatus);

        } catch (APIException $exception) {
            APILogger::addError('NCIP Message exception thrown.', [$exception->getMessage()]);
            return $this->getResponse()->withJson(new CheckinRequestErrorResponse(
                400,
                'ncip-checkin-error',
                'NCIP exception thrown',
                $exception
            ))->withStatus(400);

        } catch (\Exception $exception) {
            $errorType = 'process-checkin-request-error';
            $errorMsg = 'Unable to process checkin request due to a problem with dependent services.';

            return $this->processException($errorType, $errorMsg, $exception, $this->getRequest());
        }
    }

    /**
     * @param CheckinRequest $checkinRequest
     * @return Response
     */
    protected function initiateCheckinRequest(CheckinRequest $checkinRequest)
    {
        // Validate request data.
        try {
            $checkinRequest->validatePostData();
        } catch (APIException $exception) {
            return $this->invalidRequestResponse($exception);
        }

        $checkinRequest->create();

        // Initiate a job for non-cancellation requests.
        if (is_null($checkinRequest->getJobId()) && $this->isUseJobService()) {
            $checkinRequest->setCheckinJobId(JobService::generateJobId($this->isUseJobService()));
            // Set jobId for proper responses for non-cancellation requests.
            $checkinRequest->setJobId($checkinRequest->getCheckinJobId());
            APILogger::addDebug(
                'Initiating job via Job Service API ReCAP checkin request.',
                ['checkinJobID' => $checkinRequest->getCheckinJobId()]
            );
            JobService::beginJob($checkinRequest);
        }

        // Log start of general checkin requests or cancel request checkins.
        $initLogMessage = 'Initiating checkin process.';
        if (is_int($checkinRequest->getCancelRequestId())) {
            $initLogMessage .= ' (CancelRequestID: ' . $checkinRequest->getCancelRequestId() . ')';
            CancelRequestLogger::addInfo($initLogMessage);
        }
    }

    /**
     * @param CheckinRequest $checkinRequest
     * @param bool           $successFlag
     */
    protected function updateCheckinRequest(CheckinRequest $checkinRequest, bool $successFlag)
    {
        // Log updates for general checkin requests or cancel request checkins.
        $updateLogMessage = 'Updating checkin request status.';
        if (is_int($checkinRequest->getCancelRequestId())) {
            $updateLogMessage .= ' (CancelRequestID: ' . $checkinRequest->getCancelRequestId() . ')';
            CancelRequestLogger::addInfo($updateLogMessage);
        }

        $checkinRequest->update(
            ['success' => $successFlag]
        );

        // Finish job processing for non-cancellation requests.
        if (!is_null($checkinRequest->getCheckinJobId()) && $this->isUseJobService()) {
            APILogger::addDebug('Updating checkin job.', ['checkinJobID' => $checkinRequest->getCheckinJobId()]);
            JobService::finishJob($checkinRequest);
            // Add processed value back for non-cancellation responses.
            $checkinRequest->removeExcludedProperties(['processed']);
        }
    }

    /**
     * @param CheckinRequest $checkinRequest
     * @return CheckinRequestErrorResponse|CheckinRequestResponse
     */
    protected function sendCircOperation(CheckinRequest $checkinRequest)
    {
        $checkinClient = new CheckinClient();
        $checkinClientResponse = $checkinClient->buildCheckinRequest($checkinRequest);

        APILogger::addDebug('API Response', $checkinClientResponse);

        $checkinRequest->addFilter(new Filter('id', $checkinRequest->getId()));
        $checkinRequest->read();

        $checkinResponse = new CheckinRequestResponse($checkinRequest);

        if ($checkinClientResponse instanceof CheckinItemErrorResponse) {
            if ($checkinClientResponse->getStatusCode() >= 400) {
                $checkinResponse = new CheckinRequestErrorResponse(
                    $checkinClientResponse->getStatusCode(),
                    'ncip-checkin-error',
                    $checkinClientResponse->getProblem()
                );
            }
        }
        $checkinResponse->setStatusCode($checkinClientResponse->getStatusCode());

        return $checkinResponse;
    }

    /**
     * @param string     $errorType
     * @param string     $errorMessage
     * @param \Exception $exception
     * @param Request    $request
     * @return \Slim\Http\Response
     */
    protected function processException($errorType, $errorMessage, \Exception $exception, Request $request)
    {
        $statusCode = 500;
        if ($exception instanceof APIException) {
            $statusCode = $exception->getHttpCode();
        }

        APILogger::addLog(
            $statusCode,
            get_class($exception) . ': ' . $exception->getMessage(),
            [
                $request->getHeaderLine('X-NYPL-Log-Stream-Name'),
                $request->getHeaderLine('X-NYPL-Request-ID'),
                (string) $request->getUri(),
                $request->getParsedBody()
            ]
        );

        if ($exception instanceof APIException) {
            if ($exception->getPrevious()) {
                $exception->setDebugInfo($exception->getPrevious()->getMessage());
            }
            APILogger::addDebug('APIException debug info.', [$exception->debugInfo]);
        }

        $errorResp = new CheckinRequestErrorResponse(
            $statusCode,
            $errorType,
            $errorMessage,
            $exception
        );

        return $this->getResponse()->withJson($errorResp)->withStatus($statusCode);
    }
}
