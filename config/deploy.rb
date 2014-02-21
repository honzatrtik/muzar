set :application,   'muzar.cz'
set :repo_url, 'ssh://deploy@bob.bandzone.cz/data/git/muzar.git'

set :deploy_to, '/data/www/staging.muzar.cz'
set :scm, :git
set :deploy_via,    :copy

set :format, :pretty
set :log_level, :debug

set :linked_files,      ['app/config/parameters.yml']
set :linked_dirs,     ['app/logs', 'web/uploads', 'vendor']


set :composer_install_flags, '--no-dev --quiet --optimize-autoloader'

namespace :deploy do

  desc 'Fix directory permissions'
  task :permissions do
    on roles(:app), in: :sequence, wait: 5 do
      execute :chmod, '-R', 'g+w', release_path
      execute :chmod, '-R', 'g+w', "#{shared_path}"
      execute :chmod, '-R', 'u+s,g+s', "#{release_path}/app/cache"
      execute :chmod, '-R', 'u+s,g+s', "#{shared_path}/app/logs"
    end
  end

  desc 'Restart'
  task :restart do
    on roles(:app), in: :sequence, wait: 5 do
      :permissions
    end
  end


  namespace :symfony do

  	desc 'Symfony: cache clear'
    task :'cache-clear' do
      on roles(:app), in: :sequence, wait: 5 do
        console = "#{release_path}/app/console"
        execute console, 'cache:clear', '--env=prod'
      end
    end

  end


end





after 'deploy:restart', 'deploy:permissions'