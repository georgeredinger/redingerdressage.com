#!/bin/bash
DB_PASSWORD=`grep DB_PASSWORD /home/george/workspace/redingerdressage.com/current/.htaccess | cut -d" " -f3`
mysql -u redingerdressage -p$DB_PASSWORD redingerdressage < /home/george/workspace/redingerdressage.com/current/redingerdressage.com.sql
