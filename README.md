
# Intername API
An API for the Intername application

## Project Setup
__Note:__ These instructions are only for using Docker for your local environment
###Requirements
Fresh versions of:
- Docker
- docker-compose
- Composer
- git
## Setup
- Clone the project to your local machine
- Navigate to your project directory `cd api`
- Copy your __.env__ file: `cp .env.example .env`
- Run `docker-compose up -d` to start your local environment
- Install packages: `docker-compose api exec composer install`
- Generate app key: `docker-compose exec api php artisan key:generate`
- Run migrations: `docker-compose exec app php artisan migrate`
- Run seeders: `docker-compose exec app php artisan db:seed`

Navigate to __http://localhost:8003__ in your browser.
You should see Intername API welcome page.

## Changing ports
It is possible to have conflicts for ports used in this project.

Ports that are used are:
- Api : __8003__
- Database : __3306__

To change ports go to your __docker-compose file and change lines__

```
  db:
    ports:
      - '3306:3306'
```

or
```
  nginx:
    ports:
      - "8003:80"
```
respectively.
