Feature: Stats voyage

  Scenario: Changer l'ordre des étapes -> de 2 à 3
    Given les pays :
      | nom       |
      | France    |
      | Etat-Unis |
    Given les destinations :
      | nom       | pays      | longitude | latitude   | prix                                    | périodes                                                                                                                                                        |
      | Paris     | France    | 2.336492  | 48.864592  | {"accommodation":"30","life cost":"20"} | {"january":"0","february":"0","march":"2","april":"2","may":"1","june":"3","july":"3","august":"3","september":"2","october":"1","november":"1","december":"1"} |
      | Lyon      | France    | 4.818846  | 45.756573  | {"accommodation":"15","life cost":"10"} | {"january":"1","february":"0","march":"2","april":"1","may":"0","june":"3","july":"2","august":"3","september":"0","october":"3","november":"3","december":"2"} |
      | Marseille | France    | 5.354511  | 43.288654  | {"accommodation":"20","life cost":"20"} | {"january":"2","february":"3","march":"3","april":"1","may":"0","june":"3","july":"3","august":"1","september":"0","october":"2","november":"2","december":"1"} |
      | New-York  | Etat-Unis | 40.732977 | -73.993414 | {"accommodation":"60","life cost":"35"} | {"january":"3","february":"2","march":"2","april":"0","may":"2","june":"3","july":"1","august":"2","september":"2","october":"1","november":"1","december":"0"} |
      | Boston    | Etat-Unis | 42.359370 | -71.059168 | {"accommodation":"50","life cost":"40"} | {"january":"0","february":"1","march":"1","april":"3","may":"2","june":"3","july":"0","august":"0","september":"1","october":"0","november":"0","december":"3"} |
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
    Then les statistiques du voyage "TDM" sont :
      | nb étapes | cout moyen | durée | date départ | date retour | nb de pays | distance |
      | 4         | 1235       | 20    | 01/01/2015  | 21/01/2015  | 2          | 13918    |



