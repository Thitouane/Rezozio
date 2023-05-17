<?php
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');

$args = new RequestParameters();
$args->defineString('author');
$args->defineString('before');
$args->defineString('count');

if(! $args->isValid()){
    produceError('argument(s) invalide(s) --> '.implode(', ', $args->getErrorMessages()));
    return;
}

try{
    $data = new DataLayer();
    if($args->author=='' && $args->before=='')
        produceResult($data->getMessages($args->count));
    else if($args->author=='')
        produceResult($data->getMessagesBefore($args->before, $args->count));
    else if($args->before=='')
        produceResult($data->findMessagesByAuthor($args->author, $args->count));
    else if($data->getUser($args->author))
        produceResult($data->findMessages($args->author, $args->before, $args->count));
    else
        produceError("{$args->author} n'existe pas.");
}
catch (PDOException $e){
    produceError($e->getMessage());
}
?>