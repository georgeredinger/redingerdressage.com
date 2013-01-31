#!/bin/bash
mysqldump --add-drop-table  -u redingerdressage -p$REDINGERDRESSAGE_MYSQL_PASSWORD redingerdressage > redingerdressage.sql
sed "s/http:\/\/redingerdressage/http:\/\/redingerdressage.com/g" redingerdressage.sql > redingerdressage.com.sql
