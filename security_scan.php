<?php
/*
Author: Adam Lyons

*/

$total = 0;
$path = '/var/www/virtual';
$path = getcwd();
print "Getting file list...\n";
$files = recursiveDirList($path);
print "Scanning files...";

foreach ($files as $filename) {
	print ".";
	$total++;
	$line_number = 0;
	#print "processing: $path/$filename\n";
	$handle = fopen("$path/$filename", "r");
	if ($handle && $filename !=  'security_scan.php') {
		while (($line = fgets($handle)) !== false) {
			// process the line read.
			$line_number++;
			$patterns = array("source=base64_decode", "eval.*base64_decode", "POST.*execgate"); 
			$regex = '/(' .implode('|', $patterns) .')/i'; 
			if (preg_match($regex, $line)) {  
				$_line = substr($line, 0, 25);
				print <<<ALERT
		####################################################################################################################################
		#           ALERT               ALERT                      ALERT                  ALERT                                            #
		####################################################################################################################################
		$path/$filename
		>> $_line

ALERT;
				$alarms["$path/$filename"][$line_number] = $line;

				echo "Disable file by CHMOD? (y/n)\n";
				$_handle = fopen ("php://stdin","r");
				$input = fgets($_handle);
				if(trim($input) == 'y'){
					chmod("$path/$filename", 0000);
				}
				echo "Thank you, continuing...\n";
				fclose($_handle);

			}
		}
	} elseif($filename ==  'security_scan.php') {
		#ignore ourselves
	} else {
		// error opening the file.
		print "OPEN FAIL: $path/$filename\n\n";
	} 
	fclose($handle);
}

print "\nScan complete ($total Files)\n";
#print_r($alarms);



function recursiveDirList($dir, $prefix = '') {
	$dir = rtrim($dir, '/');
	$result = array();

	foreach (glob("$dir/*", GLOB_MARK) as $f) {
		if (substr($f, -1) === '/') {
			#print "\n$f";
			$result = array_merge($result, recursiveDirList($f, $prefix . basename($f) . '/'));
		} else {
			$patterns = array("php$", "js$"); 
			$regex = '/(' .implode('|', $patterns) .')/i'; 
			if(preg_match($regex,$f)) {
				if (substr(decoct(fileperms($f)), -3) !== '000') {
					$result[] = $prefix . basename($f);
					#print ".";
				}
			}
		}
	}
	return $result;
}

?>
