<?php
namespace System\Http;

/**
 * Class JsonResponse
 *
 * This class represents a JSON response that can be returned from an HTTP request.
 * It extends the base Response class and formats the response body as a JSON string.
 */
class JsonResponse extends Response {
    
    /**
     * JsonResponse constructor.
     *
     * @param array $body The data to be encoded as JSON and included in the response body.
     * @param int $statusCode The HTTP status code for the response. Defaults to 200 (OK).
     * @param array $headers Optional additional headers to include in the response.
     */
    public function __construct(array $body=[], int $statusCode = 200, array $headers = []) {
        parent::__construct(json_encode($body), $statusCode, $headers);
    }
}