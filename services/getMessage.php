<?php
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');

$args = new RequestParameters();
$args->defineInt('messageId');

if(! $args->isValid()){
    produceError('argument(s) invalide(s) --> '.implode(', ', $args->getErrorMessages()));
    return;
}

try{
    $data = new DataLayer();
    $msg = $data->getMessage($args->messageId);
    if($msg)
        produceResult($msg);
    else    
        produceError("{$args->messageId} n'est pas un identifiant correcte.");
}
catch (PDOException $e){
    produceError($e->getMessage());
}
?>