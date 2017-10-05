Feature: Stages

    Scenario: Supprimer une étapes
        Given les monnaies :
            | nom               | code |
            | Euro              | EUR  |
            | Dollard Américain | USD  |
        Given les pays :
            | nom       | capitale   | monnaie |
            | France    | Paris      | EUR     |
            | Belgique  | Bruxelles  | EUR     |
            | Etat-Unis | Washington | USD     |
        Given les destinations :
            | nom       | pays      |
            | Paris     | France    |
            | Lyon      | France    |
            | Marseille | France    |
            | New-York  | Etat-Unis |
            | Boston    | Etat-Unis |
            | Bruxelles | Belgique  |
        Given les possibilitées de transports :
            | depuis    | jusqu'à  | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Marseille | New-York | 599        | 859         |            |             |          |           |
            | New-York  | Lyon     | 710        | 529         |            |             |          |           |
            | Marseille | Lyon     | 207        | 211         | 66         | 212         | 24       | 280       |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        When l'utilisateur "guilhem" crée les voyages suivants :
            | nom | date de départ | destination de départ |
            | TDM | 01/01/2015     | Paris                 |
        When j'ajoute les étapes suivantes au voyage "TDM" :
            | destination | pays     | nombre de jour |
            | Boston      |          | 1              |
            | Paris       |          | 2              |
            | Boston      |          | 3              |
            | Lyon        |          | 4              |
            | Marseille   |          | 5              |
            | New-York    |          | 6              |
            | Lyon        |          | 7              |
            |             | Belgique | 8              |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à  | type de transport |
            | Marseille | New-York | FLY               |
            | New-York  | Lyon     | FLY               |
        When je supprime l'étape "New-York" à la position 6 du voyage "TDM"
        Then la voyage "TDM" à les étapes suivantes :
            | destination | pays     | nombre de jour | position |
            | Boston      |          | 1              | 1        |
            | Paris       |          | 2              | 2        |
            | Boston      |          | 3              | 3        |
            | Lyon        |          | 4              | 4        |
            | Marseille   |          | 5              | 5        |
            | Lyon        |          | 7              | 6        |
            |             | Belgique | 8              | 7        |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à | type de transport |
            | Marseille | Lyon    | BUS               |


    Scenario: Changer l'ordre des étapes -> de 2 à 3
        Given les monnaies :
            | nom               | code |
            | Euro              | EUR  |
            | Dollard Américain | USD  |
        Given les pays :
            | nom       | capitale   | monnaie |
            | France    | Paris      | EUR     |
            | Etat-Unis | Washington | USD     |
        Given les destinations :
            | nom       | pays      |
            | Paris     | France    |
            | Lyon      | France    |
            | Marseille | France    |
            | New-York  | Etat-Unis |
            | Boston    | Etat-Unis |
        Given les possibilitées de transports :
            | depuis   | jusqu'à   | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Paris    | Lyon      | 1          | 10          |            |             |          |           |
            | Lyon     | Marseille | 1          | 10          |            |             |          |           |
            | New-York | Marseille |            |             |            |             | 1        | 10        |
            | New-York | Boston    | 1          | 10          |            |             |          |           |
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
        When je change l'étape "Marseille" du voyage "TDM" de la position 2 à la position 3
        Then la voyage "TDM" à les étapes suivantes :
            | destination | nombre de jour | position |
            | Lyon        | 7              | 1        |
            | New-York    | 8              | 2        |
            | Marseille   | 3              | 3        |
            | Boston      | 2              | 4        |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis   | jusqu'à   | type de transport |
            | Paris    | Lyon      | FLY               |
            | New-York | Marseille | BUS               |

    Scenario: Changer l'ordre des étapes -> de 4 à 1
        Given les monnaies :
            | nom               | code |
            | Euro              | EUR  |
            | Dollard Américain | USD  |
        Given les pays :
            | nom       | capitale   | monnaie |
            | France    | Paris      | EUR     |
            | Etat-Unis | Washington | USD     |
        Given les destinations :
            | nom       | pays      |
            | Paris     | France    |
            | Lyon      | France    |
            | Marseille | France    |
            | Dijon     | France    |
            | New-York  | Etat-Unis |
            | Boston    | Etat-Unis |
        Given les possibilitées de transports :
            | depuis    | jusqu'à  | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Paris     | New-York | 1          | 10          |            |             |          |           |
            | Lyon      | Dijon    |            |             | 10         | 1           |          |           |
            | Marseille | Boston   |            |             |            |             | 1        | 10        |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        When l'utilisateur "guilhem" crée les voyages suivants :
            | nom | date de départ | destination de départ |
            | TDM | 01/01/2015     | Paris                 |
        When j'ajoute les étapes suivantes au voyage "TDM" :
            | destination | nombre de jour |
            | Lyon        | 7              |
            | Dijon       | 3              |
            | Marseille   | 13             |
            | New-York    | 8              |
            | Boston      | 2              |
        When je change l'étape "New-York" du voyage "TDM" de la position 4 à la position 1
        Then la voyage "TDM" à les étapes suivantes :
            | destination | nombre de jour | position |
            | New-York    | 8              | 1        |
            | Lyon        | 7              | 2        |
            | Dijon       | 3              | 3        |
            | Marseille   | 13             | 4        |
            | Boston      | 2              | 5        |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à  | type de transport |
            | Paris     | New-York | FLY               |
            | Lyon      | Dijon    | TRAIN             |
            | Marseille | Boston   | BUS               |

