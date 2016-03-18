Zend Payroll Application
========================

Description
-----------
This project is a basic payroll application that is built ontop of Zend Framework 2 (ZF2) skeleton application
using the ZF2 MVC layer and module systems.It allows one to add numerous personnel and tasks (jobs) available to these personnel. Various task can then be assigned to individual personnel with different rates, or many personnel can be assigned to the same task. The application then allows the user to enter the work done for a task by a specific personnel; this includes the hours worked, the date the work was done, the location the work was done at (for off-site jobs) and the period
in which the work was done. A period in the context of this application is defined as a fortnight starting from January 1 of each year with a total of 27 periods per year. From the work done a list of payrolls are calculated for the last period.
That is the period prior to the current one which is dependent on the current date.

Screens of the Application
----------------------------

![Alt text](/screens/screenshot-1.png?raw=true "Home Page")

![Alt text](/screens/screenshot-2.png?raw=true "Personnel Page")

SetUp
-----
To get a working copy locally on your machine simply git clone the repository or download the zip file.
install composer and navigate to the directory containing the project where you may run:

    composer install

This should install the dependencies (ZF2) in the composer.json file.
Then proceed to run the application with document root in public/ and directory index as index.php using the server of
your chose. The instructions below might help you if you use apache or nginx or php's commandline server.

Use [Composer](https://getcomposer.org/). If you don't have it already installed, then please install as per the [documentation](https://getcomposer.org/doc/00-intro.md).

To give the application access to the database (the test database is under database_sample in the project root)
go to /config/autoload/ and copy the local.php.dist file and rename the copy as local.php within the same folder.
Uncomment the return statement and add:

    return array(
        'db' => array(
            'username' => <database user>,
            'password' => <database password>,
        )
    );
    
Other database settings can be found in global.php.

Web server setup
----------------

### PHP CLI server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root
directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

**Note:** The built-in CLI server is *for development only*.

### Vagrant server

This project supports a basic [Vagrant](http://docs.vagrantup.com/v2/getting-started/index.html) configuration with an inline shell provisioner to run the Skeleton Application in a [VirtualBox](https://www.virtualbox.org/wiki/Downloads).

1. Run vagrant up command

    vagrant up

2. Visit [http://localhost:8085](http://localhost:8085) in your browser

Look in [Vagrantfile](Vagrantfile) for configuration details.

### Apache setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-app.localhost
        DocumentRoot /path/to/zf2-app/public
        <Directory /path/to/zf2-app/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
            <IfModule mod_authz_core.c>
            Require all granted
            </IfModule>
        </Directory>
    </VirtualHost>

### Nginx setup

To setup nginx, open your `/path/to/nginx/nginx.conf` and add an
[include directive](http://nginx.org/en/docs/ngx_core_module.html#include) below
into `http` block if it does not already exist:

    http {
        # ...
        include sites-enabled/*.conf;
    }


Create a virtual host configuration file for your project under `/path/to/nginx/sites-enabled/zf2-app.localhost.conf`
it should look something like below:

    server {
        listen       80;
        server_name  zf2-app.localhost;
        root         /path/to/zf2-app/public;

        location / {
            index index.php;
            try_files $uri $uri/ @php;
        }

        location @php {
            # Pass the PHP requests to FastCGI server (php-fpm) on 127.0.0.1:9000
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_param  SCRIPT_FILENAME /path/to/zf2-app/public/index.php;
            include fastcgi_params;
        }
    }

Restart the nginx, now you should be ready to go!
