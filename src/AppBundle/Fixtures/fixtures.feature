Feature: Fixtures

    Scenario: Fixtures
        Given les utilisateurs :
            | nom   | mot de passe | email               | role             |
            | gui   | gui          | gimli.fr@hotmail.fr | ROLE_SUPER_ADMIN |
            | user  | user         | user@test.com       | ROLE_USER        |
            | admin | admin        | admin@test.com      | ROLE_ADMIN       |
