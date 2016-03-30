Feature: Fixtures

    Scenario: Fixtures
        Given les utilisateurs :
            | nom   | mot de passe | email               | role             |
            | gui   | gui          | gimli.fr@hotmail.fr | ROLE_SUPER_ADMIN |
            | user  | user         | user@test.com       | ROLE_USER        |
            | admin | admin        | admin@test.com      | ROLE_ADMIN       |
        Given les monnaies :
            | nom                  | code |
            | Rand                 | ZAR  |
            | Dinar algérien       | DZD  |
            | Euro                 | EUR  |
            | Peso argentin        | ARS  |
            | Dollar australien    | AUD  |
            | Dollar bélizien      | BZD  |
            | Kyat                 | MMK  |
            | Boliviano            | BOB  |
            | Réal brésilien       | BRL  |
            | Lev bulgare          | BGN  |
            | Riel                 | KHR  |
            | Dollar canadien      | CAD  |
            | Peso chilien         | CLP  |
            | Yuan                 | CNY  |
            | Peso colombien       | COP  |
            | Colón costaricien    | CRC  |
            | Kuna ou Euro         | HRK  |
            | Peso cubain          | CUP  |
            | Livre égyptienne     | EGP  |
            | Dirham               | AED  |
            | Dollar américain     | USD  |
            | Quetzal              | GTQ  |
            | Forint               | HUF  |
            | Roupie indienne      | INR  |
            | Roupie indonésienne  | IDR  |
            | Couronne islandaise  | ISK  |
            | Shekel               | ILS  |
            | Yen                  | JPY  |
            | Dinar jordanien      | JOD  |
            | Shilling kényan      | KES  |
            | Kip laotien          | LAK  |
            | Ringgit              | MYR  |
            | Dirham marocain      | MAD  |
            | Peso mexicain        | MXN  |
            | Tugrik               | MNT  |
            | Roupie népalaise     | NPR  |
            | Cordoba              | NIO  |
            | Couronne norvégienne | NOK  |
            | Dollar néo-zélandais | NZD  |
            | Sol                  | PEN  |
            | Riyal qatarien       | QAR  |
            | Couronne tchèque     | CZK  |
            | Leu roumain          | RON  |
            | Livre sterling       | GBP  |
            | Rouble russe         | RUB  |
            | Dollar de Singapour  | SGD  |
            | Couronne suédoise    | SEK  |
            | Franc Suisse         | CHF  |
            | Baht                 | THB  |
            | Dinar tunisien       | TND  |
            | Livre turque         | TRY  |
            | Bolivar vénézuélien  | VEF  |
            | Dong                 | VND  |
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
        Given les possibilitées de transports :
            | depuis | jusqu'à   | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Lyon   | Marseille | 207        | 211         | 66         | 212         | 24       | 280       |
