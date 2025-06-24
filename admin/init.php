<?php

//file to directory files root

include "connect.php";

$ft = "include/templet/";
$flang = "include/lang/";
$tpnav = "include/templet/";
$fun = "include/function/";
$fs = "layout/css/";
$fj = "layout/js/";

include $fun . "function.php";

include $flang . "eng.php";

include $ft . "header.php";




if (!isset($notNav)) {
    include $tpnav . "nav-bar.php";
}


?>