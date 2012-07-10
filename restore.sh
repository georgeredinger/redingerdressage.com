#!/bin/bash
source ~/Dropbox/secrets.sh
mysql -u redingerdressage -p$REDINGERDRESSAGE_MYSQL_PASSWORD redingerdressage < rd.redinger.me.sql
