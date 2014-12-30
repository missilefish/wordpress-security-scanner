<?php
/*
Author: Adam Lyons
Source: https://github.com/missilefish/wordpress-security-scanner

The source updates often, be sure to check the project page on Git from time to time. 
*/
$time_start = microtime(true);

$_filename = 'security_scan.php';
$alarms = array();
$interactive = 0;
$force = 0;

/* if you want to get your own notifications just update $email='your@dot.com' */
// If you detect any new patterns please share on GIT 
$email_encoded = 'YWRhbUBtaXNzaWxlZmlzaC5jb20';
$email = base64_decode(strtr($email_encoded, '-_', '+/'));
$total = 0; $force=0;

// Script example.php
$shortopts  = "";
$shortopts .= "f::";  // Required value
$shortopts .= "p::"; // Optional value
$shortopts .= "h"; // These options do not accept values

$longopts  = array(
    "force::",     // Required value
    "prompt::",    // Optional value
    "help",        // No value
);
$opts = getopt($shortopts, $longopts);
var_dump($opts);

foreach (array_keys($opts) as $opt) switch ($opt) {
  case 'force':
    // Do something with s parameter
    $force= $opts['force'];
    break;
  case 'prompt':
    // Do something with s parameter
    $interactive= $opts['prompt'];
    break;
  case 'h':
    //print_help_message();
    exit(1);
}

print "Int: $interactive\nForce: $force\n\n";

if($interactive) {
	print "Alerts will cause a pause in script execution, to disable run without any arguments\n\n";
} else {
	print "WARNING: This script is NOT running interactive, only an email report will be generated. To enable run with ./$_filename prompt=1\n\n";
}

if($force) {
	print "WARNING: FORCE ACTIVE - no prompting for CHMOD 0000 operations\n\n\n";
}


$path = getcwd();
print "Getting file list...\n";
$files = recursiveDirList($path);
print "Scanning files...";

foreach ($files as $filename) {
	print ".";
	$total++;
	$line_number = 0;
	#print "processing: $path/$filename\n";
	$break = explode('/', $filename);
	$c_filename = $break[count($break) - 1]; 
	if($c_filename !==  $_filename) {
		$handle = fopen("$path/$filename", "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				// process the line read.
				$line_number++;
				$patterns = array("source=base64_decode", 
					"eval.*base64_decode", 
					"POST.*execgate",
					"touch\(\"wp-optionstmp.php\"",
					"file_put_contents.*wp-options",
					"touch.*wp-options\.php",
					"@move_uploaded_file\(",
					"code_inject_sape",
					"xmlrpc.php\".*mktime\(",
					"jquery.php\".*mktime\(",
					"exec\(\"find\ ",
					"exec\(\'find\ ",
					"assert\((\"|\')e(\"|\')\.(\"|\')v(\"|\')",
					"\(gzinflate\(str_rot13\(base64_decode",
					"preg_replace\((\"|\')\/\.\*\/e(\"|\')\,(\"|\')",
					"\\\x62\\\\x61\\\\x73\\\\x65\\\\x36\\\\x34\\\\x5f\\\\x64\\\\x65\\\\x63\\\\x6f\\\\x64\\\\x65",
					"\\\\x65\\\\x76\\\\x61\\\\x6C\\\\x28\\\\x67\\\\x7A\\\\x69\\\\x6E\\\\x66\\\\x6C\\\\x61\\\\x74\\\\x65\\\\x28\\\\x62\\\\x61\\\\x73\\\\x65\\\\x36\\\\x34\\\\x5F\\\\x64\\\\x65\\\\x63\\\\x6F\\\\x64\\\\x65\\\\x28"
				); 


				$regex = '/(' .implode('|', $patterns) .')/i'; 
				if (preg_match($regex, $line, $matches)) {  
					interact($line, $path, $filename, $line_number, $matches);
				}

				preg_match_all('/(\'[a-z0-9]\')\=>(\'[a-z0-9]\')/i', $line, $foo) . "\n";

				if(count($foo[1]) == count($foo[2])) {
					if(count($foo[1]) > 5) {
						print "Detected ROT13 Suspect\n" . "instances: " . count($foo[1]) . "\n";
						interact($line, $path, $filename, $line_number, null);
					}
				}

			}
			fclose($handle);
		} else {
			// error opening the file.
			print "OPEN FAIL: $path/$filename\n\n";
		} 
	}
}

$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;

$msg = 'Total Execution Time:'.$execution_time.' Mins';

$msg .= "\nScan complete ($total Files)\n\n\n";

$date = date('l jS \of F Y h:i:s A');
if($alarms) {
	#print "The following alarms occured:\n";
	#print_r($alarms);
	$body = "$msg\n\nAlarms detected on $date\n\n" . print_r($alarms, true);
	$to = $email; $subject = "Wordpress Security Scanner ($_filename) Security Report";  
	if (mail($to, $subject, $body)) {   echo("Email successfully sent!\n$body\n");  } else {   echo("Email delivery failed.\n");  }	
} else {
	#$body = "$msg\n\nNo alarms detected: $date";
}

function interact($line, $path, $filename, $line_number, $matches) {
	global $interactive;
	global $force;
	$_line = substr($line, 0, 50);
	$_matches = print_r($matches, true);
	print <<<ALERT

####################################################################################################################################
#           ALERT               ALERT                      ALERT                  ALERT                                            #
$_matches
$path/$filename
>> $_line
####################################################################################################################################

ALERT;
	$alarms["$path/$filename"]["$filename"][$line_number] = $line;

	if($interactive) {
		echo "Disable file by CHMOD? (y/n)\n";
		$_handle = fopen ("php://stdin","r");
		$input = fgets($_handle);
		if(trim($input) == 'y'){
			chmod("$path/$filename", 0000);
		}
		echo "Thank you, continuing...\n";
		fclose($_handle);
	}
	if($force) {
		chmod("$path/$filename", 0000);
		echo "$path/$filename updated to 0000, continuing...\n";
	}
}


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
