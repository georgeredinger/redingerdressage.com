Redinger Dressage Wordpress site
====
# todos
* setup development staging production workflow  

++ sync code and db and system dependancies such as names and passwords for db.
on source machine
mysqldump -u [uname] -p[pass] [dbname] > [backupfile.sql]
 transfer backupfile.sql to dest machine
then, on destination machine.
mysql - u [uname] -p[pass] [dbname] < [backfile.sql]

* google calendar with list of upcomming events
* contact form with google map
* header image sourced from flickr cycles through tags
* header image segmented flickr photos number of segments responsive to browser width
* youtube embed helper

