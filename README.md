Redinger Dressage Wordpress site
====
# todos
* setup development staging production workflow  

++ sync code and db and system dependancies such as names and passwords for db.
on source machine
mysqldump  -u redingerdressage -p$REDINGERDRESSAGE_MYSQL_PASSWORD redingerdressage > redingerdressage.sql
scp redingerdressage.sql george@rd.redinger.me:/home/george/

mysql -u redingerdressage -p$REDINGERDRESSAGE_MYSQL_PASSWORD redingerdressage < redingerdressage.sql

* google calendar with list of upcomming events
* contact form with google map
* header image sourced from flickr cycles through tags
* header image segmented flickr photos number of segments responsive to browser width
* youtube embed helper

