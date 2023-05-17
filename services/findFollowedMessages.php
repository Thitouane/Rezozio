<?php
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');
require('lib/watchdog_service.php');

$args = new RequestParameters();
$args->defineString('before');
$args->defineNonEmptyString('count');

if(! $args->isValid()){
    produceError('argument(s) invalide(s) --> '.implode(', ', $args->getErrorMessages()));
    return;
}

try{
    $data = new DataLayer();
    if($args->before=='')
        $search = $data->findFollowedMessagesBeforeless($args->count);
    else
        $search = $data->findFollowedMessages($args->before, $args->count);
    produceResult($search);
}
catch (PDOException $e){
    produceError($e->getMessage());
}
?>