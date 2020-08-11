
## Installation Instructions

- ports 8088,4306,9000,6379 should not be occupied by other service before running of docker (You can change ports in docker-compose.yml)
- run docker-compose up in termainal

## Api Call examples

[GET] http://localhost:8088/api/countries  - returns all countries with wikipedia and youtube data

[GET] http://localhost:8088/api/countries?page=1&limit=1 - returns paginated data

[GET] http://localhost:8088/api/countries/1 - returns single country with wikipedia any youtube data 

[GET] http://localhost:8088/api/countries/code/gb - returns single country with wikipedia any youtube data by region_code 


## Additional Information

You can clear cache by running following command: docker-compose run --rm artisan cache:clear

"initial_paragraphs" -  is property that represent wikipedia data
"videos"  - is property that represent youtube data

command for running tests - docker-compose run --rm php vendor/bin/phpunit

If you have issue with permissions run

sudo chmod -R 777 storage/*

