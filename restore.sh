#!/bin/bash

mysql -u redingerdressage -p$REDINGERDRESSAGE_MYSQL_PASSWORD redingerdressage < redingerdressage.sql
