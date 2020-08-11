
## Installation

- ports 8088,4306,9000,6379 should not be occupied by other service before running of docker (You can change ports in docker-compose.yml)
- run docker-compose up in termainal

## Api Call examples

[GET] http://localhost:8088/api/countries  - returns all countries with wikipedia and youtube data

[GET] http://localhost:8088/api/countries/1 - returns single country with wikipedia any youtube data 

## Additional Information

You can clear cache by running following command: docker-compose run --rm artisan cache:clear


If you have issue with permissions run

sudo chmod -R 777 storage/*

