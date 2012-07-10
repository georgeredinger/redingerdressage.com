#!/bin/bash
mysqldump --add-drop-table  -u redingerdressage -p$REDINGERDRESSAGE_MYSQL_PASSWORD redingerdressage > redingerdressage.sql
sed "s/http:\/\/redingerdressage/http:\/\/rd.redinger.me/g" redingerdressage.sql > rd.redinger.me.sql
