Feature: Auja Configurator
  In order to easily setup Auja
  As a developer
  I want some magic to happen!

  Scenario: Setup Auja with a single model.

    Given there is a model "Club"
    And it has the following columns:
      | column | type    |
      | id     | integer |
      | name   | string  |

    When I configure the Auja Configurator

    Then I should get a configuration with the following models:
      | name |
      | Club |
    And there should be no relations.

  Scenario: Setup Auja with two models without relations.

    Given there is a model "Club"
    And it has the following columns:
      | column | type    |
      | id     | integer |
      | name   | string  |
    And there is a model "Shop"
    And it has the following columns:
      | column | type    |
      | id     | integer |
      | name   | string  |

    When I configure the Auja Configurator

    Then I should get a configuration with the following models:
      | name |
      | Club |
      | Shop |
    And there should be no relations.

  Scenario: Setup Auja with two models with a single belongs to relation.

    Given there is a model "Club"
    And it has the following columns:
      | column | type    |
      | id     | integer |
      | name   | string  |
    And there is a model "Team"
    And it has the following columns:
      | column  | type    |
      | id      | integer |
      | name    | string  |
      | club_id | integer |

    When I configure the Auja Configurator

    Then I should get a configuration with the following models:
      | name |
      | Club |
      | Team |
    And it should have a belongs to relationship between:
      | left | right |
      | Team | Club  |
    And it should have a has many relationship between:
      | left | right |
      | Club | Team  |


  Scenario: Setup Auja with two models with a one-to-one relation.

    Given there is a model "Club"
    And it has the following columns:
      | column  | type    |
      | id      | integer |
      | name    | string  |
      | team_id | integer |
    And there is a model "Team"
    And it has the following columns:
      | column  | type    |
      | id      | integer |
      | name    | string  |
      | club_id | integer |

    When I configure the Auja Configurator

    Then I should get a configuration with the following models:
      | name |
      | Club |
      | Team |
    And it should have a belongs to relationship between:
      | left | right |
      | Team | Club  |
      | Club | Team  |


  Scenario: Setup Auja with three models with transitive belongs to relations.

    Given there is a model "Club"
    And it has the following columns:
      | column | type    |
      | id     | integer |
      | name   | string  |
    And there is a model "Team"
    And it has the following columns:
      | column  | type    |
      | id      | integer |
      | name    | string  |
      | club_id | integer |
    And there is a model "Player"
    And it has the following columns:
      | column  | type    |
      | id      | integer |
      | name    | string  |
      | team_id | integer |

    When I configure the Auja Configurator

    Then I should get a configuration with the following models:
      | name   |
      | Club   |
      | Team   |
      | Player |
    And it should have a belongs to relationship between:
      | left   | right |
      | Team   | Club  |
      | Player | Team  |
    And it should have a has many relationship between:
      | left | right  |
      | Club | Team   |
      | Team | Player |

  Scenario: Setup Auja with two models with a many-to-many relation.

    Given there is a model "Team"
    And it has the following columns:
      | column | type    |
      | id     | integer |
      | name   | string  |
    And there is a model "Match"
    And it has the following columns:
      | column | type     |
      | id     | integer  |
      | date   | datetime |
    And there is a pivot table "match_team"

    When I configure the Auja Configurator

    Then I should get a configuration with the following models:
      | name  |
      | Team  |
      | Match |
    And it should have a many to many relationship between:
      | left | right |
      | Team | Match |