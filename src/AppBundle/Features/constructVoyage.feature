Feature: construct voyage

    Scenario: Create voyage
        Given les pays :
            | nom    | description | bon plans |
            | France |             |           |
        Given les destinations :
            | nom   | pays   | description | bon plans | longitude | latitude  | prix                                    | périodes                                                                                                                                                        |
            | Paris | France |             |           | 2.336492  | 48.864592 | {"accommodation":"32","life cost":"24"} | {"january":"1","february":"1","march":"2","april":"2","may":"2","june":"3","july":"3","august":"3","september":"2","october":"1","november":"1","december":"1"} |
