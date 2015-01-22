Vagrant.configure("2") do |config|


	config.vm.define "elasticsearch" do |v|
		v.vm.provider "docker" do |d|
			d.name = "elasticsearch"
			d.build_dir = "./docker/elasticsearch"
			d.build_args = ["--force-rm=false"]
			d.volumes = ["/var/docker/elasticsearch:/data"]
			d.ports = ["9200:9200"]
			d.vagrant_vagrantfile = "./Vagrantfile.proxy"
	  	end
	end


	config.vm.define "mysql" do |v|
		v.vm.provider "docker" do |d|
			d.name = "mysql"
			d.image = "tutum/mysql"
			d.volumes = ["/var/docker/mysql:/var/lib/mysql"]
			d.ports = ["3306:3306"]
			d.vagrant_vagrantfile = "./Vagrantfile.proxy"
			d.env = {
				'MYSQL_PASS'  => 'muzar',
				'MYSQL_USER' => 'muzar',
				'STARTUP_SQL' => '/docker/startup.sql',
		  	}
	  	end

	  	v.vm.synced_folder "./docker/mysql", "/docker"
	end


	config.vm.define "apache-php" do |v|
		v.vm.provider "docker" do |d|

			d.name = "apache"
			d.build_dir = "./docker/apache-php"
			d.build_args = ["--force-rm=false"]
			d.ports = ["80:80"]
			d.vagrant_vagrantfile = "./Vagrantfile.proxy"
			d.link("elasticsearch:elasticsearch")
			d.link("mysql:mysql")
			d.env = {
				'SYMFONY_ENV'  => 'docker',
		  	}

			d.cmd = ["php", "/data/app/console", "doctrine:schema:drop", "--force"]
			d.cmd = ["php", "/data/app/console", "doctrine:schema:update", "--force"]
			d.cmd = ["php", "/data/app/console", "doctrine:fixtures:load", "--no-interaction"]
			d.cmd = ["php", "/data/app/console", "fos:elastica:populate"]
			d.cmd = ["php", "/data/app/console", "cache:clear", "--env=docker", "--no-warmup"]
			d.cmd = ["/run.sh"]

		end

		v.vm.synced_folder ".", "/data", owner: "www-data", group: "www-data"

	end
end  