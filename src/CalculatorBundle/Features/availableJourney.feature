Feature: Available Journey calculator

    Scenario: Récupérer et stocker les trajets
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

