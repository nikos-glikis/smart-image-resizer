<?php
//enable php debugging
$debug = false;
//log errors to $logFile
$logErrors = false;
//on error a one pixel image will be saved whenever possible.
$onePixelImageOneError = true;
$logFile = 'errors.log';

//driver can be 'gd' or 'imagick'
//gd is slower but more common. Needs php-gd library installed.
//  Centos 7: yum install php-gd
//  Ubuntu: sudo apt-get install php5-gd
//  Ubuntu: sudo apt-get install php-gd
//imagick is faster but needs imagemagick installed
$driver = 'gd';