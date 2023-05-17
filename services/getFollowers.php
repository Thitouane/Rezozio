<?php
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');
require('lib/watchdog_service.php');

try{
    $data = new DataLayer();
    $res = $data->getFollowers();
    produceResult($res);
}
catch (PDOException $e){
    produceError($e->getMessage());
}
?>