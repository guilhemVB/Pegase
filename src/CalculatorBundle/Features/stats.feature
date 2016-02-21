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
            | nom       | pays      | longitude | latitude   | prix de l'hébergement | prix du cout de la vie |
            | Paris     | France    | 2.336492  | 48.864592  | 30                    | 20                     |
            | Lyon      | France    | 4.818846  | 45.756573  | 15                    | 10                     |
            | Marseille | France    | 5.354511  | 43.288654  | 20                    | 20                     |
            | New-York  | Etat-Unis | 40.732977 | -73.993414 | 60                    | 35                     |
            | Boston    | Etat-Unis | 42.359370 | -71.059168 | 50                    | 40                     |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        Given les voyages de l'utilisateur "guilhem"
            | nom | date de départ | destination de départ |
            | TDM | 01/01/2015     | Paris                 |
        Given les étapes suivantes au voyage "TDM" :
            | destination | nombre de jour |
            | Lyon        | 7              |
            | Marseille   | 3              |
            | New-York    | 8              |
            | Boston      | 2              |
        Then les statistiques du voyage "TDM" sont :
            | nb étapes | cout total | durée | date départ | date retour | nb de pays | distance | destination principale |
            | 4         | 1235       | 20    | 01/01/2015  | 21/01/2015  | 2          | 13918    | New-York               |



