Feature: Fixtures

    Scenario: Fixtures AvailableJourney
        Given entities "CalculatorBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Paris                                             | Lyon                                            | 136       | 269     | 82          | 152       | 21        | 452     |
            | Paris                                             | Londres                                         | 111       | 319     | 235         | 205       | 47        | 587     |
            | Paris                                             | New York                                        | 469       | 725     |             |           |           |         |
            | Lyon                                              | Paris                                           | 136       | 270     | 83          | 133       | 21        | 458     |
            | Lyon                                              | Londres                                         | 150       | 321     | 261         | 342       | 32        | 1081    |
            | Lyon                                              | New York                                        | 483       | 837     |             |           |           |         |
            | Lyon                                              | Nice                                            | 99        | 73      | 150         | 300       | 51        | 712     |
            | Londres                                           | Paris                                           | 114       | 311     | 235         | 201       | 52        | 616     |
            | Londres                                           | Lyon                                            | 153       | 294     | 253         | 407       | 38        | 988     |
            | Londres                                           | New York                                        | 496       | 681     |             |           |           |         |
            | Londres                                           | Dublin                                          | 89        | 102     |             |           | 35        | 440     |
            | New York                                          | Paris                                           | 469       | 622     |             |           |           |         |
            | New York                                          | Lyon                                            | 483       | 778     |             |           |           |         |
            | New York                                          | Londres                                         | 493       | 638     |             |           |           |         |


    Scenario: Fixtures Voyage Tour dè Frânce
        Given entities "CalculatorBundle\Entity\Voyage" :
            | name           | AppBundle\Entity\User:username | startDate(\DateTime) | StartDestination:AppBundle\Entity\Destination:name |
            | Tour dè Frânce | gui                            | 2017-10-12 20:30:54  | Paris                                              |
        Given entities "CalculatorBundle\Entity\Stage" :
            | CalculatorBundle\Entity\Voyage:name | AppBundle\Entity\Destination:name | AppBundle\Entity\Country:name | nbDays | position |
            | Tour dè Frânce                      | Lyon                              |                               | 4      | 1        |
            | Tour dè Frânce                      | Nice                              |                               | 3      | 2        |
            | Tour dè Frânce                      | Bordeaux                          |                               | 5      | 3        |
            | Tour dè Frânce                      | Paris                             |                               | 1      | 4        |


    Scenario: Fixtures Voyage Tour d'Eur@pe
        Given entities "CalculatorBundle\Entity\Voyage" :
            | name          | AppBundle\Entity\User:username | startDate(\DateTime) | StartDestination:AppBundle\Entity\Destination:name |
            | Tour d'Eur@pe | gui                            | 2017-09-18 08:30:00  | Nice                                               |
        Given entities "CalculatorBundle\Entity\Stage" :
            | CalculatorBundle\Entity\Voyage:name | AppBundle\Entity\Destination:name | AppBundle\Entity\Country:name | nbDays | position |
            | Tour d'Eur@pe                       |                                   | France                        | 4      | 1        |
            | Tour d'Eur@pe                       |                                   | Royaume-Uni                   | 3      | 2        |
            | Tour d'Eur@pe                       |                                   | Irlande                       | 5      | 3        |
            | Tour d'Eur@pe                       | Stockholm                         |                               | 1      | 4        |
            | Tour d'Eur@pe                       | Amsterdam                         |                               | 3      | 5        |
            | Tour d'Eur@pe                       |                                   | Danemark                      | 3      | 5        |