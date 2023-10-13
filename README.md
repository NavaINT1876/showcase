# Example small project from Ihor Kucherenko

## Installation (Linux)

Run command to get up and running the project:
```bash
docker-compose up -d
```

Then install all dependencies:

```bash
docker-compose run nu-app composer install --ignore-platform-reqs
```

Then run migrations:

```bash
docker-compose run nu-app php artisan migrate
```

Then publish Swagger packages and generate docs:

```bash
docker-compose run nu-app php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
docker-compose run nu-app php artisan l5-swagger:generate
```

After that you will be able to open application on http://localhost:3075 and check API documentations page out.

Also there is a Postman collection in the project root called "Ihor Test.postman_collection.json" so you can import
and test using even it.

Have fun and Happy checking! :))

