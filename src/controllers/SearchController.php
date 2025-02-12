<?php
require_once __DIR__ . '/../models/DomainSearchModel.php';

class SearchController {
    private $domainSearchModel;

    // Constructor to initialize the model
    public function __construct() {
        $this->domainSearchModel = new DomainSearchModel(); // Initialize the model here
    }

    public function index() {
        require __DIR__ . '/../views/search.php';
    }

    public function searchDomains() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get the raw POST data
            $jsonData = file_get_contents('php://input');

            // Decode the JSON data into a PHP array
            $data = json_decode($jsonData, true);

            // Check if the data is valid
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(["error" => "Invalid JSON data"]);
                return;
            }

            // Initialize an empty array for domain names and extensions
            $domainNames = [];

            // Loop through each item in the data array
            foreach ($data as $item) {
                $domainName = $item['name'];   // The domain name
                $extension = $item['extension']; // The extension (TLD)

                $domainNames[] = ['name' => $domainName, 'extension' => $extension];
            }

            $results = $this->domainSearchModel->searchDomains($domainNames);

            // Return the results as JSON
            echo json_encode($results);
        }
    }
}
