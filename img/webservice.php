<?php
include_once("webservice/webservice.php");

$apikey = tep_get_value_post("api");

include_once("webservice/{$apikey}.php");

$claimApi = new ClaimApi();
$claimApi->run();
