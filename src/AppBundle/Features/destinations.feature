Feature: Destinations

    Scenario: Trouver les dernières destinations complètes
        Given les monnaies :
            | nom               | code |
            | Euro              | EUR  |
            | Dollard Américain | USD  |
        Given les pays :
            | nom       | capitale   | monnaie |
            | France    | Paris      | EUR     |
            | Etat-Unis | Washington | USD     |
        Given les destinations :
            | nom         | pays      | partielle |
            | Paris       | France    | non       |
            | Montpellier | France    | oui       |
            | Lyon        | France    | non       |
            | New-York    | Etat-Unis | oui       |
            | Miami       | Etat-Unis | oui       |
            | Boston      | Etat-Unis | non       |
        Then les dernières destinations complètes ajoutées sont :
            | nom    |
            | Boston |
            | Lyon   |
            | Paris  |
        When je modifie les destinations :
            | nom         | pays      | partielle |
            | Montpellier | France    | non       |
            | New-York    | Etat-Unis | non       |
            | Miami       | Etat-Unis | non       |
        Then les dernières destinations complètes ajoutées sont :
            | nom         |
            | Montpellier |
            | New-York    |
            | Miami       |
