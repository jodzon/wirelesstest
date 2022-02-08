# wirelesstest
gathers data from given url
uses ``` laravel-zero/framework ``` version ^8.8 and ``` weidner/goutte ``` version ^2.1
## Install:
``` composer install```

### Start: 
``` docker-compose up ```
### Stop:
``` docker-compose down ```
### Scrap:
```
docker exec -it app bash
cd /var/www/html 
php application scrap --u https://videx.comesconnected.com/
```
### Test:
```
docker exec -it app bash
cd /var/www/html 
php application test
```
