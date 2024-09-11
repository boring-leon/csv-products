## About

This is a recruitment task.
For requirements reference the [requirements](requirements.md) file inside this repository.

My thought processes and additional ideas are documented within the code.

## Installation

This project setup requires you have `Docker` and `Docker Compose`
installed on your host machine.

1. Clone the repo and `cd` into its root directory
2. Create `env` file `cp .env.example .env`
3. Install depedencies:

     ```
     ./vendor/bin/sail up -d
     ./vendor/bin/sail composer install
     ```

4. Generate app key `./vendor/bin/sail artisan key:generate`
5. Set up mysql database connection
   ```
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=sail
    DB_PASSWORD=password
   ```
6. Run database migrations `./vendor/bin/sail artisan migrate`
7. Start the Laravel Sail environment if it isn’t running yet `./vendor/bin/sail up -d`

## Running the command

1. Open shell as sail user `./vendor/bin/sail shell`
2. Execute the command inside running app container
   ```
   php artisan products:import --path=stock_valid.csv
   ```

## Command options

There two example csv files:

* `stock_invalid.csv` - original csv file provided for the task.
* `stock_valid.csv` - original csv fixed to not have any issues

Feel free to use them for testing.

Command options are:

```
--path -> Path to a csv file (required)
--strict -> Stops script execution and prints errors if any were detected while validating projects. (default no)
--test -> Will not actually insert processed records into the database. (default no - will insert)
```

## Testing

To run unit tests:

1. Open shell as sail user `./vendor/bin/sail shell`
2. Execute the command inside running app container
   ```
   php artisan test
   ```

