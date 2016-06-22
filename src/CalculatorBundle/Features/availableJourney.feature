Feature: Available Journey calculator

    Scenario: Récupérer et stocker et mettre à jour les trajets
        Given les monnaies :
            | nom               | code |
            | Euro              | EUR  |
            | Dollard Américain | USD  |
            | Livre sterling    | GBP  |
        Given les pays :
            | nom          | capitale   | monnaie |
            | France       | Paris      | EUR     |
            | Etat-Unis    | Washington | USD     |
            | Royaume-Unis | Londres    | GBP     |
        Given les destinations :
            | nom      | pays         | longitude   | latitude   |
            | Paris    | France       | 2.2946583   | 48.8580101 |
            | Lyon     | France       | 4.8492387   | 45.7635056 |
            | Londres  | Royaume-Unis | -0.0775694  | 51.5082493 |
            | New-York | Etat-Unis    | -73.9862683 | 40.7590453 |
        When je lance la récupération des transports possibles
        Then les possibilitées de transports sont :
            | depuis   | jusqu'à  | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Paris    | Lyon     | 136        | 269         | 82         | 152         | 21       | 452       |
            | Paris    | Londres  | 111        | 319         | 235        | 205         | 47       | 587       |
            | Paris    | New-York | 469        | 725         |            |             |          |           |
            | Lyon     | Paris    | 136        | 270         | 83         | 133         | 21       | 458       |
            | Lyon     | Londres  | 150        | 321         | 261        | 342         | 32       | 1081      |
            | Lyon     | New-York | 483        | 837         |            |             |          |           |
            | Londres  | Paris    | 114        | 311         | 235        | 201         | 52       | 616       |
            | Londres  | Lyon     | 153        | 294         | 253        | 407         | 38       | 988       |
            | Londres  | New-York | 496        | 681         |            |             |          |           |
            | New-York | Paris    | 469        | 622         |            |             |          |           |
            | New-York | Lyon     | 483        | 778         |            |             |          |           |
            | New-York | Londres  | 493        | 638         |            |             |          |           |
        When je supprime les transports liés à la destination "Lyon"
        Then les possibilitées de transports sont :
            | depuis   | jusqu'à  | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Paris    | Londres  | 111        | 319         | 235        | 205         | 47       | 587       |
            | Paris    | New-York | 469        | 725         |            |             |          |           |
            | Londres  | Paris    | 114        | 311         | 235        | 201         | 52       | 616       |
            | Londres  | New-York | 496        | 681         |            |             |          |           |
            | New-York | Paris    | 469        | 622         |            |             |          |           |
            | New-York | Londres  | 493        | 638         |            |             |          |           |


    Scenario: Mettre à jour les voyages après l'ajout d'un trajets
        Given les monnaies :
            | nom  | code |
            | Euro | EUR  |
        Given les pays :
            | nom    | capitale | monnaie |
            | France | Paris    | EUR     |
        Given les destinations :
            | nom       | pays   |
            | Paris     | France |
            | Lyon      | France |
            | Marseille | France |
            | Dijon     | France |
        Given les possibilitées de transports :
            | depuis | jusqu'à   | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Lyon   | Marseille | 207        | 211         | 66         | 212         | 24       | 280       |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        When l'utilisateur "guilhem" crée les voyages suivants :
            | nom | date de départ | destination de départ |
            | TDM | 01/01/2015     | Paris                 |
        When j'ajoute les étapes suivantes au voyage "TDM" :
            | destination | nombre de jour |
            | Lyon        | 1              |
            | Marseille   | 1              |
            | Dijon       | 1              |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis | jusqu'à   | type de transport |
            | Lyon   | Marseille | BUS               |
        Given les possibilitées de transports :
            | depuis    | jusqu'à | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Paris     | Lyon    | 52         | 56          | 50         | 120         | 5        | 630       |
            | Marseille | Dijon   | 52         | 56          | 50         | 120         | 5        | 630       |
        When je met à jour les voyages avec les trajets disponibles
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à   | type de transport |
            | Paris     | Lyon      | BUS               |
            | Lyon      | Marseille | BUS               |
            | Marseille | Dijon     | BUS               |
        When je supprime les transports liés à la destination "Dijon"
        Then les possibilitées de transports sont :
            | depuis   | jusqu'à   | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Paris    | Lyon      | 52         | 56          | 50         | 120         | 5        | 630       |
            | Lyon     | Marseille | 207        | 211         | 66         | 212         | 24       | 280       |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à   | type de transport |
            | Paris     | Lyon      | BUS               |
            | Lyon      | Marseille | BUS               |

    @skip
    Scenario: debug calcul trajet disponible
        When j'affiche le trajet trouvé entre "san-francisco" et "yosemite-park"
