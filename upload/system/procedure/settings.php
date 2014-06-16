<?php

// Require PHP5.1 or Higher
if (version_compare(phpversion(), '5.1.0', '<') == true) {
    exit('PHP5.1+ Required');
}

// Default to UTC Timezone
if (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}

?>