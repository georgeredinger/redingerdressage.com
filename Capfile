require 'capistrano/version'
load 'deploy'

# You need to fill in the 2 vars below
set :domain,  "rd.redinger.me"
set :user,    "george"
set :application, "redingerdressage.com"
set :repository, "git@github.com:georgeredinger/redingerdressage.com.git"

server "#{domain}", :app, :web, :db, :primary => true

set :deploy_via, :remote_cache
set :copy_exclude, [".git", ".DS_Store"]
set :scm, :git
set :branch, "master"
set :deploy_to, "/home/#{user}/workspace/#{application}"
set :use_sudo, false
set :keep_releases, 5
set :git_shallow_clone, 1

# use this option to point to any ssh key you have setup
#ssh_options[:keys] = [ File.join( File.expand_path('~'), ".ssh", "slicehost" ) ]
ssh_options[:paranoid] = false

namespace :deploy do

	desc <<-DESC
	A macro-task that updates the code and fixes the symlink.
	DESC
	task :default do
		transaction do
			update_code
			create_symlink
		  upload_htaccess
		end
	end

	task :update_code, :except => { :no_release => true } do
		on_rollback { run "rm -rf #{release_path}; true" }
		strategy.deploy!
	end

desc "upload .htaccess to remote"
	task :upload_htaccess do
		puts ("scp .htaccess  #{user}@#{domain}:#{current_path}/.htaccess")
		system ("scp .htaccess  #{user}@#{domain}:#{current_path}/.htaccess")
		#sudo chown -v :www-data "/enterYourFilePathHere/.htaccess"
		#sudo chmod -v 664 "/enterYourFilePathHere/.htaccess"

	end

	task :after_deploy do
		cleanup
	end
  
	task :after_symlink do
		run "ln -nfs #{shared_path}/uploads/" \
			" #{current_path}/wp-content/uploads"
	end
	desc "Pull a database dump from remote server, drop the local database, then import the dump"
	task :pull_database_to_local do
		# Build out temporary file name with timestamp for uniqueness
		timestamp = get_timestamp
		temp_file_name = "database_dump_#{timestamp}"

		remote_file_name = remote_mysqldump(temp_file_name)

		download(remote_file_name, "/tmp/#{temp_file_name}.sql.gz")

		system("gunzip /tmp/#{temp_file_name}.sql.gz")

		# You may need to modify some of the data to match local URLs here
		# system(%Q{sed -i -e "s@http://remote.url/@http://local.url/@g" /tmp/#{temp_file_name}.sql})

		puts "Backing up previous database to /tmp/previous_database_#{timestamp}.sql.gz"
		system("mysqldump -uroot local_database_name | gzip -9 > /tmp/previous_database_#{timestamp}.sql.gz")
		system("mysql -uroot local_database_name < /tmp/#{temp_file_name}.sql")
		system("rm -rf /tmp/#{temp_file_name}*")

		run "rm -rf #{deploy_to}/#{shared_dir}/#{temp_file_name}.tar.gz"
	end
	desc "Push a database dump from local server,  to the remote server and import to on the remote"
	task :push_database_to_remote do
	system "source ~/Dropbox/secrets.sh"
  system "mysqldump --add-drop-table  -u redingerdressage -p$REDINGERDRESSAGE_MYSQL_PASSWORD redingerdressage > redingerdressage.sql"
 system 'sed "s/http:\/\/redingerdressage/http:\/\/rd.redinger.me/g" redingerdressage.sql > rd.redinger.me.sql'
system "scp rd.redinger.me.sql george@chicago.redinger.me:/home/george/workspace/redingerdressage.com/current/"
run "source ~/Dropbox/secrets.sh"
run "mysql -u redingerdressage -p$REDINGERDRESSAGE_MYSQL_PASSWORD redingerdressage < rd.redinger.me.sql"

	end

	desc "Push all images from local, backup remote images, and copy local images into remote install"
	task :push_uploads_to_remote do
	end
	
	# You'll need to set up config[:local_file_path] to map to your local WordPress directory
	desc "Pull all images from staging, backup local images, and copy staging images into local install"
	task :pull_uploads_to_local do
		timestamp = get_timestamp
		temp_file_name = "temp_uploads_#{timestamp}"
		temp_file_dir = File.join(deploy_to, shared_dir)
		temp_file_path = File.join(temp_file_dir, temp_file_name)

		# Make the temp directory on the remote server
		run "mkdir -p #{temp_file_path}"
		# Copy all uploads to temp directory
		run "cp -r #{current_path}/docs/wp-content/uploads/* #{temp_file_path}"
		# Tar/Zip it up
		run "cd #{temp_file_dir} && tar -czvf #{temp_file_path}.tar.gz #{temp_file_name}"
		# Download it locally
		download("#{temp_file_path}.tar.gz", "/tmp/#{temp_file_name}.tar.gz")
		puts "Extracting downloaded uploads"
		# Extract the files
		system("cd /tmp && tar -xzvf #{temp_file_name}.tar.gz")
		puts "Backing up existing uploads to /tmp/existing_uploads_#{timestamp}"

		# Create uploads directory locally if it doesn't exist
		unless File.directory?("#{config[:local_file_path]}/wp-content/uploads")
			puts "Creating empty uploads directory, since it does not exist yet"
			system("cd #{config[:local_file_path]}/wp-content && mkdir uploads")
		end
		# Back up your existing uploads
		system("cp -r #{config[:local_file_path]}/wp-content/uploads /tmp/existing_uploads_#{timestamp}")
		puts "Overwriting local uploads path"
		# Clear and copy over uploads
		system("rm -rf #{config[:local_file_path]}/wp-content/uploads/*")
		system("cp -r /tmp/#{temp_file_name}/* #{config[:local_file_path]}/wp-content/uploads/")
		# May need to update permissions
		# system("chmod -R 775 #{config[:local_file_path]}/wp-content/uploads/*")

		# Delete temp files
		run "rm -rf #{temp_file_path}*"
	end

	def remote_mysqldump(file_path)
		# Get database configuration info, however you store it
		database = config[stage][:database_name]
		user = config[stage][:database_user]
		password = config[stage][:database_password]

		# Run the command
		run "mysqldump -u#{user} -p#{password} --lock-tables=FALSE #{database} | gzip -9 > #{file_path}"
	end 

	def local_mysqldump(file_path)
	#	# Get database configuration info, however you store it
	#	database = config[stage][:database_name]
	#	user = config[stage][:database_user]
	#	password = config[stage][:database_password]

	#	# Run the command
	#	run "mysqldump -u#{user} -p#{password} --lock-tables=FALSE #{database} | gzip -9 > #{file_path}"
	end 


end

