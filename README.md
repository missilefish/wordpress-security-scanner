wordpress security scanner
==========================

A PHP script to check for hacked files in a Wordpress installation. The script will read every line in every file from the origin in which you run it (the script, recursively), by default will set file and directory permissions to a relatively safe level, and alert you to issues. In the event a file is flagging, the default solution is to set the mode to '0000'. This mode makes the file useless, however it is NOT a complete solution to your problem. The intention with this script is to provide automated resolutions depending on how you implement it under CRON. 

This script should be run as SUPERUSER.

This script is designed for system admins and webmasters who deal with multiple virtual hosts who have Wordpress installs. If you don't have a basic understanding of Worpdress and PHP you might want to move along.

If you have not moved along, then this script is a pulic share of how I manage my hundreds of virtual hosts which server WP. 

This script is not perfect, it is not paticularily elegant, but it runs stand-alone and doesn't need a thing (but php). 

HOW TO:

	Read the source and update the $wpgui and $wpui etc variables which need updating most likely 
	Run this script fom the DOCROOT of an instance. 

TODO: I know the usage statement is garbage, I just don't have much feedback on how other people have used it and I mainly run it from CRON to give me alarms. However, the script is growing, and I use it to de-secure an instance, and re-secure it after updates, while trying to maintain my end user abilities to uploads media and manage basic content (/wp-content/{etc}).

IMPORTANT:

	There are basically two things I would like you to do at some point.
		1) Update the email address which is encoded in the script head portion
		2) Update the file ownership which relates to your world of WP. I do not allow FTP updates at will with folks (shared), so I run as ROOT and NGINX, in the appropriate way. I am all ears as to how to do that better. Essentially this script allows me to LOCK or UNLOCK an instance for auto-updates, etc. 

	This script will:
		Change owners
		Change groups
		Change modes
		Send email
		BREAK YOUR WP INSTALL DUE TO A DEFAULT LOCKDOWN OF WP-CONFIG.PHP (if you don't run your web process as nginx)

NOT IMPORTANT:

	This is a work in progress, and I update things at will based on my own observations. The email alerts help me find things often, and I update patterns and things. So, if you find an issue, or have any comment, I will see it so long as you email me or comment. I am open to collaboration on this script.


