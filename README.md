# LAMP stack built with Docker Compose

This repository provides a basic LAMP stack environment built using Docker Compose. It consists of the following:

- PHP
- Apache
- MySQL (via MariaDB)
- phpMyAdmin

It is based on the following repo: https://github.com/sprintcube/docker-compose-lamp

Extra PHP and MySQL versions have been removed along with redis, and the project structure has been simplified.

## Installation
Make sure you have docker installed first. Download the latest docker desktop install here: https://www.docker.com/products/docker-desktop/.

Note that you do NOT have to create a docker account to download docker desktop or to use this project.

- Clone this repository on your local computer (or just download a zip archive)
- Rename cs404.env to .env (this file contains environment variables for docker-compose)
- Run `docker-compose up -d` from the root of the project
- Visit `http://localhost:3000` in your browser

You can edit the files in `www` to see changes reflected in the browser. PHP 8.2 is supported.

## Credit
This is a copy of the sprintcube docker-compose-lamp repo, with some modifications to suit the needs of our class.
For more information, visit the original repo: https://github.com/sprintcube/docker-compose-lamp
