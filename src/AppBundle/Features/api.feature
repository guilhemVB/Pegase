Feature: API
    https://behat-api-extension.readthedocs.io/en/latest/
    Avant de lancer le test : php bin/console server:run --env=test

    Scenario: CRUD currency
        Given the request body is:
            """
            {"name":"Euro", "code":"EUR"}
            """
        And the "Content-Type" request header is "application/json"
        When I request "/api/currency" using HTTP POST
        Then the response body contains JSON:
            """
            {"id":1,"name":"Euro", "code":"EUR","eurRate":null,"usdRate":null}
            """
        When I request "/api/currencies" using HTTP GET
        Then the response body contains JSON:
            """
            [{"id":1,"name":"Euro", "code":"EUR","eurRate":null,"usdRate":null}]
            """
        When I request "/api/currency/1" using HTTP GET
        Then the response body contains JSON:
            """
            {"id":1,"name":"Euro", "code":"EUR","eurRate":null,"usdRate":null}
            """
        Given the request body is:
            """
            {"name":"Euro", "code":"RUO"}
            """
        When I request "/api/currency/1" using HTTP PUT
        Then the response body contains JSON:
            """
            {"id":1,"name":"Euro", "code":"RUO","eurRate":null,"usdRate":null}
            """
        When I request "/api/currency/1" using HTTP GET
        Then the response body contains JSON:
            """
            {"id":1,"name":"Euro", "code":"RUO","eurRate":null,"usdRate":null}
            """
        When I request "/api/currency/1" using HTTP DELETE
        When I request "/api/currency/1" using HTTP GET
        Then the response code is 404
        When I request "/api/currencies" using HTTP GET
        Then the response body contains JSON:
            """
            []
            """
