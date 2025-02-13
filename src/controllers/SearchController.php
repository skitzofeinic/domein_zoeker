<?php
/**
 * SearchController class.
 *
 * This class handles operations related to searching for domains, including displaying
 * the search page and processing domain search requests.
 *
 */

require_once __DIR__ . '/../models/DomainSearchModel.php';

class SearchController
{
    private $domainSearchModel;

    public function __construct()
    {
        $this->domainSearchModel = new DomainSearchModel();
    }

    /**
     * Display the search page.
     *
     * This method renders the search view.
     *
     * @return void
     */
    public function index()
    {
        require __DIR__ . '/../views/search.php';
    }

    /**
     * Search for domains.
     *
     * This method processes a POST request to search for domains based on the provided
     * domain names and extensions.
     * It expects a JSON payload containing an array of domain data.
     * The method returns the search results in JSON format.
     *
     * @return void
     */
    public function searchDomains()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["error" => "Invalid JSON data"]);
            return;
        }

        $domainNames = [];
        foreach ($data as $item) {
            $domainName = $item['name'];
            $extension = $item['extension'];

            $domainNames[] = ['name' => $domainName, 'extension' => $extension];
        }

        $results = $this->domainSearchModel->searchDomains($domainNames);
        echo json_encode($results);
    }
}
