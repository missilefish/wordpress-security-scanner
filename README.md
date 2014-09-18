wordpress-security-scanner
==========================

A basic PHP script to check for hacked files in a Wordpress installation

The path used in this script is the CWD. Example:

 cd /root;
 
 git clone https://github.com/missilefish/wordpress-security-scanner;
 
 cd /var/www/virtual && php /root/wordpress-security-scanner/security_scan.php;


Hit 'y' in the event you get a detection. This will set the file permissions to 000 and disable the file for the webserver, while preserving the content so you can investigate. 

Many common files penetrated in Wordpress by bad permissions or other reasons are common theme files such as 'footer.php' - watch out for disabling them, often there is just a small insertion at the end you can easily wipe. 
