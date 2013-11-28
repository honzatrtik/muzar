set   :application,   "Muzar"
set   :deploy_to,     "/data/www/staging.muzar.cz"
set   :domain,        "trta@staging.muzar.cz"

set   :scm,           :git
set   :repository,    "ssh://trta@bob.bandzone.cz/data/git/muzar.git"
set   :deploy_via,    :copy

role  :web,           domain
role  :app,           domain, :primary => true

set   :use_sudo,      false
set   :keep_releases, 3

set	:shared_files,      ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads", "vendor"]
set :use_composer, true
set :update_vendors, true

logger.level = Logger::MAX_LEVEL