set :application,   'muzar.cz'
set :repo_url, 'ssh://deploy@bob.bandzone.cz/data/git/muzar.git'

set :deploy_to, '/data/www/staging.muzar.cz'
set :scm, :git
set :deploy_via,    :copy

set :format, :pretty
set :log_level, :debug

# set :linked_files,      ['app/config/parameters.yml']
set :linked_dirs,     ['app/logs', 'web/uploads', 'vendor']

namespace :deploy do

  desc 'Restart application'
  task :restart do
#    on roles(:app), in: :sequence, wait: 5 do
#      # Your restart mechanism here, for example:
#      # execute :touch, release_path.join('tmp/restart.txt')
#    end
  end

#  after :published, :restart

#  after :restart, :clear_cache do
#    on roles(:web), in: :groups, limit: 3, wait: 10 do
#      # Here we can do anything such as:
#      # within release_path do
#      #   execute :rake, 'cache:clear'
#      # end
#    end
#  end

end