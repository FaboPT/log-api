# Log API

## Requirements

- [Docker](https://www.docker.com/products/docker-desktop)
- [Postman](https://www.postman.com/downloads/)

## Info

- [Symfony Info](https://symfony.com/doc/6.4/setup.html)

## Installation/Configuration

Change database configurations in **.env**

```
DB_DATABASE=yourdatabasename
DB_USERNAME=root
DB_PASSWORD=yourpassword
DATABASE_URL="mysql://root:yourpassword@mysql:3306/yourdatabasename?serverVersion=8&charset=utf8mb4"

```

### Detach the application

```
docker-compose up -d
```

### Install composer dependencies

```
docker-compose exec app composer install
```


### Open browser http://localhost:8888


### Configure Access Local Database

```
Host: 127.0.0.1
Port: 3308
Username: root
Password: yourpassword
```

## Commands for devs

```
docker-compose exec app php bin/console
```

## Extras

### Alternative to install composer dependencies

#### macOS / Linux

```
docker run --rm -v $(pwd):/app composer install
```

#### Windows (Git Bash)

```
docker run --rm -v /$(pwd):/app composer install
```

#### Windows (Powershell)

```
docker run --rm -v ${PWD}:/app composer install
```
