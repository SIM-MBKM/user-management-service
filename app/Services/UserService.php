<?php

namespace App\Services;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use SIMMBKM\ModService\Service as BaseService;

class UserService extends BaseService
{
    protected $baseUri = 'USER_MANAGEMENT_URL';

    public static function getUserData($userId)
    {
        try {
            $instance = new self();

            // Log attempt to connect
            Log::info("Attempting to connect to user service for user ID: {$userId}");

            // Add debug information for connection
            Log::debug("Connection details", [
                'base_uri' => env($instance->baseUri),
                'endpoint' => "/api/v1/user/service/by-user-id/{$userId}"
            ]);

            // Set a more generous timeout for debugging purposes
            $instance->client = new \GuzzleHttp\Client([
                'base_uri' => rtrim(env($instance->baseUri, 'localhost'), '/') . '/',
                'headers' => $instance->getHeaders(),
                'http_errors' => false,
                'timeout' => 15, // Increased timeout for debugging
                'connect_timeout' => 5,
                'debug' => env('APP_DEBUG', false) // Enable debug mode in development
            ]);

            // Make request with explicit logging
            Log::info("Sending request to: " . rtrim(env($instance->baseUri, 'localhost'), '/') . "/api/v1/user/service/by-user-id/{$userId}");
            $start = microtime(true);

            $response = $instance->client->get("api/v1/user/service/by-user-id/{$userId}");

            $duration = microtime(true) - $start;
            Log::info("Request completed in {$duration} seconds");

            // Log response
            $statusCode = $response->getStatusCode();
            Log::info("Received response with status code: {$statusCode}");

            $responseBody = (string) $response->getBody();
            Log::debug("Response body", ['body' => $responseBody]);

            $decodedResponse = json_decode($responseBody);

            // Check for valid JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("JSON decode error: " . json_last_error_msg());
                throw new \Exception("Invalid JSON response: " . json_last_error_msg());
            }

            return $decodedResponse;
        } catch (ConnectException $e) {
            // This is specifically for connection issues
            Log::error("Connection error to user service: " . $e->getMessage(), [
                'userId' => $userId,
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);

            return (object) [
                'status' => 'error',
                'message' => "Unable to connect to user service. Please check network connectivity and service availability."
            ];
        } catch (RequestException $e) {
            // This captures all Guzzle request exceptions
            $errorContext = [
                'userId' => $userId,
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ];

            // Add response info if available
            if ($e->hasResponse()) {
                $errorContext['status_code'] = $e->getResponse()->getStatusCode();
                $errorContext['response_body'] = (string) $e->getResponse()->getBody();
            }

            Log::error("Request error to user service: " . $e->getMessage(), $errorContext);

            return (object) [
                'status' => 'error',
                'message' => "Error communicating with user service: " . $e->getMessage()
            ];
        } catch (\Exception $e) {
            // Catch all other exceptions
            Log::error("Error in UserService: " . $e->getMessage(), [
                'userId' => $userId,
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);

            return (object) [
                'status' => 'error',
                'message' => "An unexpected error occurred: " . $e->getMessage()
            ];
        }
    }
}
