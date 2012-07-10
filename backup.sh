#!/bin/bash
mysqldump --add-drop-table  -u redingerdressage -p$REDINGERDRESSAGE_MYSQL_PASSWORD redingerdressage > redingerdressage.sql
