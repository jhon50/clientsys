<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('vendor/autoload.php');

use Setra\Models\ConnectionManager;
use Setra\Models\Client;

$conn = ConnectionManager::get();
Client::setConnection($conn);

$clientId = isset($_GET['id']) ? $_GET['id'] : null; 


if($clientId){
    $client = new Client($clientId);
    $client->delete();
    header('Location: /index.php');
}

    
var_dump($client);
?>