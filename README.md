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
heroku run php app/console doctrine:schema:update --force

heroku run php app/console app:import:countries web/files/pays.csv
heroku run php app/console app:import:destinations web/files/destinations.csv
heroku run php app/console app:import:typicalVoyage web/files/voyages_types.csv

heroku run php app/console assetic:dump web --env=prod
heroku run php app/console cache:clear
```