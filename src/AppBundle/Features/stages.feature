Feature: Stages

    Scenario: Supprimer une étapes
        Given les pays :
            | nom       |
            | France    |
            | Etat-Unis |
        Given les destinations :
            | nom       | pays      |
            | Paris     | France    |
            | Lyon      | France    |
            | Marseille | France    |
            | New-York  | Etat-Unis |
            | Boston    | Etat-Unis |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        Given les voyages de l'utilisateur "guilhem"
            | nom | date de départ | destination de départ | nombre de voyageur |
            | TDM | 01/01/2015     | Paris                 | 1                  |
        Given les étapes suivantes au voyage "TDM" :
            | destination | nombre de jour |
            | Lyon        | 7              |
            | Marseille   | 3              |
            | New-York    | 8              |
        When je supprime l'étape "Marseille" à la position 2 du voyage "TDM"
        Then la voyage "TDM" à les étapes suivantes :
            | destination | nombre de jour | position |
            | Lyon        | 7              | 1        |
            | New-York    | 8              | 2        |


    Scenario: Changer l'ordre des étapes -> de 2 à 3
        Given les pays :
            | nom       |
            | France    |
            | Etat-Unis |
        Given les destinations :
            | nom       | pays      |
            | Paris     | France    |
            | Lyon      | France    |
            | Marseille | France    |
            | New-York  | Etat-Unis |
            | Boston    | Etat-Unis |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        Given les voyages de l'utilisateur "guilhem"
            | nom | date de départ | destination de départ | nombre de voyageur |
            | TDM | 01/01/2015     | Paris                 | 1                  |
        Given les étapes suivantes au voyage "TDM" :
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


    Scenario: Changer l'ordre des étapes -> de 4 à 1
        Given les pays :
            | nom       |
            | France    |
            | Etat-Unis |
        Given les destinations :
            | nom       | pays      |
            | Paris     | France    |
            | Lyon      | France    |
            | Marseille | France    |
            | New-York  | Etat-Unis |
            | Boston    | Etat-Unis |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        Given les voyages de l'utilisateur "guilhem"
            | nom | date de départ | destination de départ | nombre de voyageur |
            | TDM | 01/01/2015     | Paris                 | 1                  |
        Given les étapes suivantes au voyage "TDM" :
            | destination | nombre de jour |
            | Lyon        | 7              |
            | Marseille   | 3              |
            | Marseille   | 13             |
            | New-York    | 8              |
            | Boston      | 2              |
        When je change l'étape "New-York" du voyage "TDM" de la position 4 à la position 1
        Then la voyage "TDM" à les étapes suivantes :
            | destination | nombre de jour | position |
            | New-York    | 8              | 1        |
            | Lyon        | 7              | 2        |
            | Marseille   | 3              | 3        |
            | Marseille   | 13             | 4        |
            | Boston      | 2              | 5        |


    Scenario: Changer l'ordre des étapes avec des doublons de destinations
        Given les pays :
            | nom       |
            | France    |
            | Etat-Unis |
        Given les destinations :
            | nom       | pays      |
            | Paris     | France    |
            | Lyon      | France    |
            | Marseille | France    |
            | New-York  | Etat-Unis |
            | Boston    | Etat-Unis |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        Given les voyages de l'utilisateur "guilhem"
            | nom | date de départ | destination de départ | nombre de voyageur |
            | TDM | 01/01/2015     | Paris                 | 1                  |
        Given les étapes suivantes au voyage "TDM" :
            | destination | nombre de jour |
            | Marseille   | 3              |
            | Marseille   | 13             |
            | Lyon        | 7              |
            | New-York    | 8              |
            | New-York    | 18             |
            | Boston      | 2              |
        When je change l'étape "New-York" du voyage "TDM" de la position 5 à la position 2
        Then la voyage "TDM" à les étapes suivantes :
            | destination | nombre de jour | position |
            | Marseille   | 3              | 1        |
            | New-York    | 18             | 2        |
            | Marseille   | 13             | 3        |
            | Lyon        | 7              | 4        |
            | New-York    | 8              | 5        |
            | Boston      | 2              | 6        |

