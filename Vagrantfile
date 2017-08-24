# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = '2'

@script = <<SCRIPT
# Fix for https://bugs.launchpad.net/ubuntu/+source/livecd-rootfs/+bug/1561250
if ! grep -q "ubuntu-xenial" /etc/hosts; then
    echo "127.1.0.1 ubuntu-xenial" >> /etc/hosts;
fi

# Install dependencies
add-apt-repository ppa:ondrej/php;
apt-get update;
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password root';
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root';
apt-get install -y apache2 git curl php7.1 php7.1-bcmath php7.1-bz2 php7.1-cli php7.1-curl php7.1-intl \
php7.1-json php7.1-mbstring php7.1-opcache php7.1-soap php7.1-sqlite3 php7.1-xml php7.1-xsl php7.1-zip \
libapache2-mod-php7.1 php-xdebug php7.1-mysql mysql-server mysql-client;

# Prep Environment
chmod -R 777 /var/www/data;
sed -i 's/APACHE_RUN_USER=www-data/APACHE_RUN_USER=ubuntu/g' /etc/apache2/envvars;
sed -i 's/APACHE_RUN_GROUP=www-data/APACHE_RUN_GROUP=ubuntu/g' /etc/apache2/envvars;
sed -i 's/display_errors = Off/display_errors = On/g' /etc/php/7.1/apache2/php.ini;

# Configure Xdebug
echo "
xdebug.remote_enable = on
xdebug.remote_connect_back = 1

# Some Misc Settings that can help
;xdebug.collect_vars = on
;xdebug.collect_params = 4
;xdebug.dump_globals = on
;xdebug.show_local_vars = on

;xdebug.dump.SERVER = on
;xdebug.dump.POST = on
;xdebug.dump.GET = on
;xdebug.dump.SESSION = on
;xdebug.dump.COOKIE = on

;xdebug.var_display_max_depth = -1
;xdebug.var_display_max_children = -1
;xdebug.var_display_max_data = -1" >> /etc/php/7.1/mods-available/xdebug.ini

# Configure Opcache for production "like" settings
echo "
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
opcache.enable_cli=1
" >> /etc/php/7.1/mods-available/opcache.ini

# Now lets turn off opcache for development
phpdismod opcache;

# Create a default db to work with
echo "CREATE DATABASE local DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;" | mysql -u root -proot

# Configure Apache
echo "<VirtualHost *:80>
	DocumentRoot /var/www/public
	AllowEncodedSlashes On

	<Directory /var/www/public>
		Options +Indexes +FollowSymLinks
		DirectoryIndex index.php index.html
		Order allow,deny
		Allow from all
		AllowOverride All
	</Directory>

	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf;
a2enmod rewrite;
service apache2 restart;

if [ -e /usr/local/bin/composer ]; then
    /usr/local/bin/composer self-update;
else
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer;
fi

# Reset home directory of vagrant user
if ! grep -q "cd /var/www" /home/ubuntu/.profile; then
    echo "cd /var/www" >> /home/ubuntu/.profile;
fi

echo "** [ZF] Run the following command to install dependencies, if you have not already:"
echo "    vagrant ssh -c 'composer install'"
echo "** [ZF] Visit http://localhost:8080 in your browser for to view the application **"
SCRIPT

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = 'ubuntu/xenial64'
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "forwarded_port", guest: 3306, host: 33306
  config.vm.network "private_network", type: "dhcp"
  config.vm.synced_folder '.', '/var/www'
  config.vm.provision 'shell', inline: @script

  config.vm.provider "virtualbox" do |vb|
    vb.customize ["modifyvm", :id, "--memory", "1024"]
    vb.customize ["modifyvm", :id, "--name", "Application Bootstrap - Ubuntu 16.04"]
  end
end
