<?php
if(array_key_exists('dblLatitude', $arrParameters)
    && array_key_exists('dblLongitude', $arrParameters)
    && array_key_exists('intAccuracy', $arrParameters)
    && array_key_exists('intTime', $arrParameters)
) {

    require_once($strProjectPath . '/classes/Yoshi.class.php');
    $dblLatitude = doubleval($arrParameters['dblLatitude']);
    $dblLongitude = doubleval($arrParameters['dblLongitude']);
    $intAccuracy = intval($arrParameters['intAccuracy']);
    $intTime = (int)$arrParameters['intTime'];

    $objYoshiClass = new Yoshi($strProjectPath);
    $blnInsertSuccess = $objYoshiClass->addLastLocation($dblLatitude, $dblLongitude, $intAccuracy, $intTime);

    $arrResponse['status'] = true;
    if($blnInsertSuccess) {
        $arrResponse['result'] = array('blnInsertSuccess' => true);
    }
    else {
        $arrResponse['result'] = array('blnInsertSuccess' => false);
    }

}
else {
    $arrResponse['error'] = 11;
}