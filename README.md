# wirelesstest


## gathers data from test site

## Install:
``` cd wirelesstest && composer install```

## Start:
``` docker-compose up ```

## Stop:
``` docker-compose down ```

## Scrap:
```
docker exec -it app bash
cd /var/www/html 
php application scrap --u https://videx.comesconnected.com/
```

## Test:
```
docker exec -it app bash
cd /var/www/html 
php application test
```