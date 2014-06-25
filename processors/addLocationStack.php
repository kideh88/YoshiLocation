<?php

require_once($strProjectPath . '/classes/Yoshi.class.php');
$objYoshiClass = new Yoshi($strProjectPath);

foreach($arrParameters as $strJsonLocation) {
    $arrLocation = json_decode($strJsonLocation, true);
    $dblLatitude = doubleval($arrLocation['dblLatitude']);
    $dblLongitude = doubleval($arrLocation['dblLongitude']);
    $intAccuracy = intval($arrLocation['intAccuracy']);
    $intTime = (int)$arrLocation['intTime'];

    $objYoshiClass->addLastLocation($dblLatitude, $dblLongitude, $intAccuracy, $intTime);
}

$arrResponse['status'] = true;
$arrResponse['result'] = array('blnInsertSuccess' => true);
