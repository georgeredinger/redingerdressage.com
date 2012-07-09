require 'capistrano/version'
load 'deploy'

# You need to fill in the 2 vars below
set :domain,  "rd.redinger.me"
set :user,    "george"
set :application, "redingerdressage.com"
set :repository, "git@github.com:georgeredinger/redingerdressage.com.git"

server "#{domain}", :app, :web, :db, :primary => true

set :deploy_via, :copy
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
      symlink
    end
  end
  
  task :update_code, :except => { :no_release => true } do
    on_rollback { run "rm -rf #{release_path}; true" }
    strategy.deploy!
  end

  task :after_deploy do
    cleanup
  end

  task :after_symlink do
    run "ln -nfs #{shared_path}/uploads/" \
      " #{current_path}/wp-content/uploads"
  end
    
end

