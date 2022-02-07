# wirelesstest
gathers data from given url

## Install:
``` composer install```

# Use if no local php
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

# Use local php is present

### Scrap:
```
php application scrap --u https://videx.comesconnected.com/
```

### Test:
```
php application test
```
