<?php

class Yoshi {

    private $_pdo;
    private $_strTablePrefix;

    // Constructor to create a connection with Data class
    public function __construct($strProjectPath) {
        require_once($strProjectPath . '/classes/Data.class.php');
        $objDataClass = new Data($strProjectPath);
        $this->_pdo = $objDataClass->pdo();
        $this->_strTablePrefix = $objDataClass->getTablePrefix();
    }

    // Creates new events in the database
    public function addLastLocation($fltLatitude, $fltLongitude, $intAccuracy, $intTime) {

        $strLocationStatement = "INSERT INTO " . $this->_strTablePrefix . "location ( `latitude`, `longitude`, "
            . "`accuracy`, `time`) "
            . "VALUES ( :lat, :long, :accuracy, :time )";
        $objLocationPDO = $this->_pdo->prepare($strLocationStatement);

        $objLocationPDO->bindValue(':lat', strval($fltLatitude), PDO::PARAM_STR);
        $objLocationPDO->bindValue(':long', strval($fltLongitude), PDO::PARAM_STR);
        $objLocationPDO->bindValue(':time', $intTime, PDO::PARAM_INT);
        $objLocationPDO->bindValue(':accuracy', $intAccuracy, PDO::PARAM_INT);
        if($objLocationPDO->execute()) {
            $intLastId = (int)$this->_pdo->lastInsertId();
            return (0 < $intLastId);
        }
        else {
            return false;
        }

    }

    public function getLastKnownLocation() {
        date_default_timezone_set('Europe/Copenhagen');
        $arrReponse = array();
        $strLastLocation = "SELECT `latitude`, `longitude`, `accuracy`, `time` FROM ". $this->_strTablePrefix . "location "
                . "ORDER BY `time` DESC LIMIT 1";
        $objLocationPDO = $this->_pdo->prepare($strLastLocation);
        if($objLocationPDO->execute()) {
            $arrResult = $objLocationPDO->fetch(PDO::FETCH_ASSOC);
            if(0 < count($arrResult)){
                $arrReponse['latitude'] = doubleval($arrResult['latitude']);
                $arrReponse['longitude'] = doubleval($arrResult['longitude']);
                $arrReponse['time'] = date('D. H:i:s', (int)$arrResult['time']);
                $arrReponse['accuracy'] = (int)$arrResult['accuracy'];
                $arrReponse['timestamp'] = $arrResult['time'];
            }
        }
        return $arrReponse;
    }


    // Calculates the distance between 2 locations
    private function _calculatePointDistance($lat1, $lon1, $lat2, $lon2, $unit) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        }
        else if ($unit == "M") {
            return $this->_ceiling(($miles * 1.609344 * 1000), 50);
        }else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    // Rounds distances to full numbers to avoid decimals
    private function _ceiling($intNumber, $intFullRound = 1)
    {
        return ( is_numeric($intNumber) && is_numeric($intFullRound) ) ? (ceil($intNumber/$intFullRound)*$intFullRound) : false;
    }

}