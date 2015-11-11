Pegase
======

Pour lancer les tests behat :

Créer la table travel_test

lancer la commande

```
bin/behat
```

Pour les langues en français :

```
sudo apt-get install php5-intl
```


Test de vulnérabilité des package :

```
php app/console security:check
```


Deploy with Heroku

```
heroku run php app/console assetic:dump web --env=prod
heroku run php app/console cache:clear
```