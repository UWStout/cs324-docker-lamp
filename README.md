# LAMP stack built with Docker Compose

This repository provides a basic LAMP stack environment built using Docker Compose. It consists of the following:

- Linux Base (Debian Buster)
- Apache (v2.4)
- PHP (v8.2)
- MySQL (via MariaDB v10.6)

It also come with phpMyAdmin preinstalled to help you manage your databases.

## Setup Docker
If you are on Windows, it is recommended to install Windows Subsystem for Linux v2 (WSL2) to improve performance:
- Type `Powershell` in the Windows search bar, then right-click and select `Run as administrator`
- In the Powershell window, run these commands:
  - `wsl --install`
  - `wsl --set-default-version 2`

This will install and configure WSL2 and a basic Ubuntu image.

On all systems, you need to have the basic Docker containerization tool installed:
- Download and install Docker Desktop: https://www.docker.com/products/docker-desktop
- You do NOT need to create a Docker account to download Docker Desktop or to use this project.

## Installation
To use this LAMP container, follow these steps:
- Clone this repository on your local computer (or just download a zip archive)
- Rename cs324.env to .env (this file contains environment variables for docker-compose)
- Run `docker-compose up -d` from the root of the project
  - Wait while it builds the container layers (patience, young grasshopper)
  - If something goes wrong during this step, see TROUBLESHOOTING below.
- Visit `http://localhost:3000` in your browser

You can edit the files in the `www` folder to see changes reflected in the browser. PHP 8.2 is supported.

You also now have a database running on port __3307__ (one MORE than the default MySQL port). Try connecting to it from the MySQL CLI, MySQL Workbench, DBeaver, or a similar client. The default root credentials are:
- Username: `root`
- Password: `databaseSU25`

## Setup Docker to use WSL (Windows only)
You must tell Docker to use WSL in its settings:
- Start Docker Desktop and Navigate to Settings
- From the General tab, check the box next to `Use WSL 2 based engine` (if it is not already checked)
- Select `Apply & Restart`

## Troubleshooting
Coming Soon

## Credit
This is a copy of the sprintcube docker-compose-lamp repo, with some modifications to suit the needs of our class. Extra PHP and MySQL versions have been removed along with redis, and the project structure has been simplified. Many unneeded packages have also been removed from the install script for the PHP layer.

For more information, visit the original repo: https://github.com/sprintcube/docker-compose-lamp
