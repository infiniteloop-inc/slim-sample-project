#!/bin/bash

echo "CREATE DATABASE IF NOT EXISTS \`"$MYSQL_DATABASE"\` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;" | "${mysql[@]}"
echo "CREATE USER '"$MYSQL_USER"'@'localhost' IDENTIFIED BY '"$MYSQL_PASSWORD"';" | "${mysql[@]}"
echo "CREATE USER '"$MYSQL_USER"'@'%' IDENTIFIED BY '"$MYSQL_PASSWORD"';" | "${mysql[@]}"
echo "GRANT ALL PRIVILEGES ON *.* TO '"$MYSQL_USER"'@'localhost';" | "${mysql[@]}"
echo "GRANT ALL PRIVILEGES ON *.* TO '"$MYSQL_USER"'@'%';" | "${mysql[@]}"
