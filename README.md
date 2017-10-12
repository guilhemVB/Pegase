Le Monde en sac
======

http://www.lemondeensac.com/

Le Monde en Sac is a travels website. It allow you to get informations about destinations and plan your travels.

Upgrade guide [UPGRADE.md](UPGRADE.md)
file.

## Docker

```
docker-compose build
docker-compose up -d --remove-orphans
```


## Tests

You have to create the database `travel_test`, then, launch the script `test.sh`

## Grunt

```
npm cache clean && npm install --no-bin-links && bower install --allow-root
grunt
```

or

```
grunt watch
```

To Deploy :

```
bash updateVersion.sh
php app/console assetic:dump
```
