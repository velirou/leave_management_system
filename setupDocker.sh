#!/bin/bash

# Step 1: Go to project root folder
echo "Navigating to project root folder..."
#cd ./leave_management_system || { echo "Failed to navigate to the project directory"; exit 1; }

# Step 2: Build Docker image
echo "Building Docker image..."
docker build -t leavesystem . || { echo "Docker build failed"; exit 1; }

# Step 3: Run MariaDB container
echo "Running MariaDB container..."
docker run -d \
  --name mariadb_db \
  -e MYSQL_ROOT_PASSWORD=1234 \
  -e MYSQL_DATABASE=leave_system \
  -e MYSQL_USER=user \
  -e MYSQL_PASSWORD=1234 \
  -v ./init_db.sql:/docker-entrypoint-initdb.d/init_db.sql \
  -v db_data:/var/lib/mysql \
  -p 3306:3306 \
  mariadb || { echo "Failed to start MariaDB container"; exit 1; }

# Step 4: Run PHP container
echo "Running PHP container..."
docker run -d \
  --name php_app \
  -p 8080:80 \
  -v ./public:/var/www/html \
  -v ./src:/var/www/html/src \
  -v ./php.ini-production:/usr/local/etc/php/php.ini-production \
  --link mariadb_db:db \
  php:8.0-apache || { echo "Failed to start PHP container"; exit 1; }

# Step 5: Restart PHP container and install extensions
echo "Installing PHP extensions..."
docker exec -it php_app docker-php-ext-install pdo pdo_mysql mysqli || { echo "Failed to install PHP extensions"; exit 1; }

echo "Restarting PHP container..."
docker restart php_app || { echo "Failed to restart PHP container"; exit 1; }

echo "Setup complete!"
