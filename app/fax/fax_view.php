<?php
//set the include path
$conf = glob("{/usr/local/etc,/etc}/fusionpbx/config.conf", GLOB_BRACE);
set_include_path(parse_ini_file($conf[0])['document.root']);

//includes files
require_once "resources/require.php";
require_once "resources/check_auth.php";
require_once "resources/paging.php";

//check permissions
if (permission_exists('fax_file_view')) {
    //access granted
}
else {
    echo "access denied";
    exit;
}
//set the fax directory
$fax_dir = $_SESSION['switch']['storage']['dir'].'/fax/'.$_SESSION['domain_name'];
$fax_extension = preg_replace('/[^0-9]/', '', $_GET['ext']);
$fax_filename = preg_replace('/[\/\\\&\%\#]/', '', $_GET['filename']);

//check if the file is in the inbox or sent directory.
if ($_GET['type'] == "fax_inbox") {
    if (file_exists($fax_dir.'/'.$fax_extension.'/inbox/'.$fax_filename)) {
        $download_filename = $fax_dir.'/'.$fax_extension.'/inbox/'.$fax_filename;
    }
}
else if ($_GET['type'] == "fax_sent") {
    if (file_exists($fax_dir.'/'.$fax_extension.'/sent/'.$_GET['filename'])) {
        $download_filename = $fax_dir.'/'.$fax_extension.'/sent/'.$fax_filename;
    }
}

$filePath=$download_filename;
$filename=$fax_filename;
header('Content-type:application/pdf');
header('Content-disposition: inline; filename="'.$filename.'"');
header('content-Transfer-Encoding:binary');
header('Accept-Ranges:bytes');
@ readfile($filePath);


?>