<?php

// Start the session
if (!isset($_SESSION)) {
    session_start();
}

// Set cart array if needed
if(!isset($_SESSION['xcart'])){
    $_SESSION['xcart'] = array();
}

// Set order array if needed
if(!isset($_SESSION['order'])){
    $_SESSION['order'] = array();
}   

// Set shipment array if needed
if(!isset($_SESSION['shipment'])){
    $_SESSION['shipment'] = array();
}   

// Sessionize home filepath
//if (isset(DIR_HOME)) {
//  $_SESSION['HOME'] = DIR_HOME;
//}

?>