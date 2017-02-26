Feature: Stats voyage

    Scenario: Calculer les stats
        Given les monnaies :
            | nom               | code |
            | Euro              | EUR  |
            | Dollard Américain | USD  |
        Given les pays :
            | nom       | capitale   | monnaie |
            | France    | Paris      | EUR     |
            | Etat-Unis | Washington | USD     |
        Given les destinations :
            | nom       | pays      | latitude  | longitude  | prix de l'hébergement | prix du cout de la vie |
            | Paris     | France    | 48.864592 | 2.336492   | 30                    | 20                     |
            | Lyon      | France    | 45.756573 | 4.818846   | 15                    | 10                     |
            | Marseille | France    | 43.288654 | 5.354511   | 20                    | 20                     |
            | New-York  | Etat-Unis | 40.732977 | -73.993414 | 60                    | 35                     |
            | Boston    | Etat-Unis | 42.359370 | -71.059168 | 50                    | 40                     |
        Given les possibilitées de transports :
            | depuis    | jusqu'à   | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Paris     | Lyon      | 52         | 56          | 50         | 120         | 5        | 390       |
            | Lyon      | Marseille | 207        | 211         | 66         | 212         | 24       | 280       |
            | Marseille | New-York  | 599        | 859         |            |             |          |           |
            | New-York  | Boston    |            |             | 195        | 279         |          |           |
            | Boston    | Paris     |            |             |            |             | 612      | 876       |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        When l'utilisateur "guilhem" crée les voyages suivants :
            | nom | date de départ | destination de départ |
            | TDM | 01/01/2015     | Paris                 |
        When j'ajoute les étapes suivantes au voyage "TDM" :
            | destination | nombre de jour |
            | Lyon        | 7              |
            | Marseille   | 3              |
            | New-York    | 8              |
            | Boston      | 2              |
            | Paris       | 1              |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à   | type de transport |
            | Paris     | Lyon      | BUS               |
            | Lyon      | Marseille | BUS               |
            | Marseille | New-York  | FLY               |
            | New-York  | Boston    | TRAIN             |
            | Boston    | Paris     | BUS               |
        Then les statistiques du voyage "TDM" sont :
            | nb étapes | cout total | durée | date départ | date retour | nb de pays | distance | destination principale |
            | 5         | 2720       | 21    | 01/01/2015  | 22/01/2015  | 2          | 12806    | New-York               |
        When je change le mode de transport à "FLY" pour le trajet de "Lyon" à "Marseille" du voyage "TDM"
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à   | type de transport |
            | Paris     | Lyon      | BUS               |
            | Lyon      | Marseille | FLY               |
            | Marseille | New-York  | FLY               |
            | New-York  | Boston    | TRAIN             |
            | Boston    | Paris     | BUS               |
        Then les statistiques du voyage "TDM" sont :
            | nb étapes | cout total | durée | date départ | date retour | nb de pays | distance | destination principale |
            | 5         | 2903       | 21    | 01/01/2015  | 22/01/2015  | 2          | 12806    | New-York               |



