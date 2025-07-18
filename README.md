Product Catalog

Project Overview

A Laravel-based product catalog system with admin and user functionality.
Supports products, services, currency rates, and CSV exports.

Prerequisites:

Docker and Docker Compose installed

Git installed

PHP 8.1+

Composer

Getting Started
1. Clone the repository

	git clone https://github.com/misalitvin/laravelProject.git
	cd laravelProject

2. Start Docker Sail

	./vendor/bin/sail up -d
This will start containers for the app, database, etc.

3. Install PHP dependencies

	./vendor/bin/sail composer install

4. Set up environment variables
Copy the example env file and configure as needed:

	cp .env.example .env
Edit .env and configure your database credential.

6. Run migrations and seed database

	./vendor/bin/sail artisan migrate --seed

7. Import currency rates

	./vendor/bin/sail artisan currency:import
   
8. Access the app
   
Open your browser to http://localhost/products page

Admin routes are prefixed with /admin

10. To export product catalog to csv file
    
	9.1 Configure mail to which catalog should be sent
   
   	9.2 Run the worker for the RabbitMQ
    
		./vendor/bin/sail artisan queue:work
   
	9.3 Press Export button and check the mail
