<?php
require_once('../models/DomainSearchModel.php');

$domainSearch = new DomainSearchModel();
$domainNames = [
    ['name' => 'example', 'extension' => 'com'],
    ['name' => 'example', 'extension' => 'nl']
];
print_r($domainSearch->searchDomains($domainNames));
