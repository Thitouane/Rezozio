<?php
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');

$args = new RequestParameters();
$args->defineNonEmptyString('searchedString');

if(! $args->isValid()){
    produceError('argument(s) invalide(s) --> '.implode(', ', $args->getErrorMessages()));
    return;
}

try{
    $data = new DataLayer();
    $search = $data->findUsers($args->searchedString);
    produceResult($search);
}
catch (PDOException $e){
    produceError($e->getMessage());
}
?>