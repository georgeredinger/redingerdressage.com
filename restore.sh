#!/bin/bash
$DB_PASSWORD=`grep DB_PASSWORD .htaccess | cut -d" " -f3`
mysql -u redingerdressage -p$DB_PASSWORD redingerdressage < rd.redinger.me.sql
