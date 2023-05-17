<?php
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');
require('lib/watchdog_service.php');

$args = new RequestParameters();
$args->defineNonEmptyString('source');

if(! $args->isValid()){
    produceError('argument(s) invalide(s) --> '.implode(', ', $args->getErrorMessages()));
    return;
}

try{
    $data = new DataLayer();
    $data->postMessage($args->source);
    produceResult($data->findNewestMessage());
}
catch (PDOException $e){
    produceError($e->getMessage());
}
?>