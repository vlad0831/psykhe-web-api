
# Development System Setup

The following pre-requisites need to be fulfilled before you proceed with the system installation.

- **PHP 7.3** or higher
- **Docker** (https://docs.docker.com/get-docker/)
- **Composer**:  Dependency manager for PHP (https://getcomposer.org/)
- **Linux or macOS**: because of docker and web server limitations, it is strongly recommended to work under these operating systems. Nevertheless, we do not discourage the use of Windows Professional Edition+IIS
- **Homebrew**: MacOS library manager, only if working under macOS (https://brew.sh/index)

Please complete the following steps to set up the API server system for development.

- Fork the repository and, when in your favorite terminal, go to the application root folder
- Copy the `.env.example` file to `.env` and adjust its content to whichever URL you will be using, which can either be a test domain name or an IP address such as [127.0.0.1](http://127.0.0.1). The default settings will work
- Run `make vendor` to download all **PHP** dependencies via **Composer**. Chances are you may be missing some required PHP extensions. If so, use `apt` (if working from Linux) or [**Pecl**](https://pecl.php.net/) if on MacOS. You can install **Pecl** via **Homebrew**
- Run `make dev-environment` to start all peripheral services such as **MySQL** and **Redis**
- Run `php artisan migrate --seed` to create the database and seed it with fake data
- Run `make run` to start the PHP server at http://127.0.0.1:8000
