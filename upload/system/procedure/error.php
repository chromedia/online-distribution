<?php

// Set Error Reporting Level to All Errors
error_reporting(E_ALL);

// Custom Error Handling Function
function error_handler($errno, $errstr, $errfile, $errline) {
    global $log;
    
    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }
        
    echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
    
    $log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);

    return true;

}
    
// Set Custom Error Handler
set_error_handler('error_handler');

?>