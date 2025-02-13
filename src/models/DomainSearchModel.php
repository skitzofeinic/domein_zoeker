<?php
/**
 * Handles domain search operations by interacting with an external API to retrieve domain search results.
 */

require_once __DIR__ . '/../config/api.php';

class DomainSearchModel
{
    private $apiUrl = API_URL . 'domains/search?with_price=true';

    /**
     * Searches for domains by sending a request to an external API with a list of domain names.
     *
     * @param array $domainNames An array of domain names to search for, each containing a 'name' and 'extension'.
     *
     * @return array The decoded response from the API, containing domain search results. If an error occurs, the response will be null.
     */
    public function searchDomains($domainNames)
    {
        $payload = json_encode($domainNames);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . API_AUTH,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
            exit();
        }
        curl_close($ch);

        return json_decode($response, true);
    }
}