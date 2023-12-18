# Employee API

## Installation

Install PHP dependencies:

```sh
composer install
```

Start Sail

```sh
./vendor/bin/sail up -d
```

Run database migrations with seeders:

```sh
./vendor/bin/sail artisan migrate:fresh --seed
```

Import postman collection from url:

```sh
http://employee-api.test/docs/collection.json
```

To authenticate requests, include an Authorization header with the value "Bearer {ACCESS_TOKEN}". To obtain an access token, use the Login endpoint with credentials:

```sh
email: test@example.com, password: password
```
