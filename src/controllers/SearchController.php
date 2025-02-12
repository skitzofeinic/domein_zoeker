<?php
require_once __DIR__ . '/../models/DomainSearchModel.php';

class SearchController {
    public function index() {
        require __DIR__ . '/../views/search.php';
    }

    public function searchDomains() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $jsonData = file_get_contents('php://input');

            // Decode the JSON data into a PHP array
            $data = json_decode($jsonData, true);

            $domainName = $data['domainName'];
            $extension = $data['extension'];

            $domainSearchModel = new DomainSearchModel();

            $domainNames = [
                ['name' => $domainName, 'extension' => $extension]
            ];

            $result = json_encode($domainSearchModel->searchDomains($domainNames));
            echo $result;
        }
    }

}