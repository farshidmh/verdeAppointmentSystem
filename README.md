# Verde Real estate appointment booking system

Verde is a real estate appointment booking system. It is a web application that allows real estate agents to manage their appointments and clients.

## Features

- Manage custoemrs
- Manage appointments
- Manage agents

## Installation

- Clone the repository
- Run `composer install`
- Run `php artisan migrate`
- Run `php artisan serve`
- Visit `http://localhost:8000` in your browser

## Environment variables

Check the `.env.example` file for the environment variables that need to be set.

- `DB_CONNECTION` - The database connection to use. Default is `mysql`
- `DB_HOST` - The database host. Default is `
- `DB_PORT` - The database port. Default is `3306`
- `DB_DATABASE` - The database name. Default is `verde`
- `DB_USERNAME` - The database username. Default is `root`
- `DB_PASSWORD` - The database password. Default is `root`
- `APP_URL` - The application URL. Default is `http://localhost:8000`
- `APP_NAME` - The application name. Default is `Verde`
- `APP_ENV` - The application environment. Default is `local`
- `APP_DEBUG` - The application debug mode. Default is `true`
- `GOOGLE_API_KEY` - The google application key.

## Postman collection

The postman collection is available upon request.

## Docker

available on: https://hub.docker.com/r/far2005/verde-appointment-system

- Run `docker-compose up -d`

## Nice to add in the future
- Calculate the distance between the agent and the customer if agent is leaving from previous appointment
- Add more tests
- get list of appointments for a specific customer
- get list of appointments between a specific agent and customer


## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

