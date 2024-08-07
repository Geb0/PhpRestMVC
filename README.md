
# PhpRestMVC

PhpRestMVC is a Rest example written in PHP with a MVC architecture.

## Requirements

PHP `8.2.10` or above for the latest version.

Composer `2.5.8` or above for the latest version.

MySQL `8.0.37`

## Installation

Put files in an HTTP server folder.

Run Composer in the application folder to initialize its packages:

```composer install```

Create and populate tables user and test in a database:

```
CREATE TABLE user (
  usr_id INT NOT NULL AUTO_INCREMENT COMMENT 'Identifier',
  usr_firstname VARCHAR(64) NOT NULL COMMENT 'First name',
  usr_lastname VARCHAR(64) NOT NULL COMMENT 'Last name',
  usr_description TEXT NULL COMMENT 'Description',
  usr_password VARCHAR(64) NOT NULL COMMENT 'Password',
  usr_active BOOLEAN NOT NULL DEFAULT true COMMENT 'Active',
  usr_key VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'Session',
  usr_timestamp INT NOT NULL DEFAULT 0 COMMENT 'Timestamp',
  CONSTRAINT pk__user PRIMARY KEY(usr_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'User';

INSERT INTO user(usr_firstname, usr_lastname, usr_password, usr_description, usr_active) VALUES
('Test1', 'First test user', 'd74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1', 'The first test active user with "pass" password', 1),
('Test2', 'Second test user', 'd74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1', 'The second test inactive user with "pass" password', 0);

CREATE TABLE test (
  test_id INT NOT NULL AUTO_INCREMENT COMMENT 'Identifier',
  test_name VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'Name',
  test_description TEXT NULL COMMENT 'Description',
  test_value INT NOT NULL DEFAULT 0 COMMENT 'Value',
  test_price DOUBLE NOT NULL DEFAULT 0 COMMENT 'Price',
  test_active BOOLEAN NOT NULL DEFAULT 1 COMMENT 'Active',
  CONSTRAINT pk__test PRIMARY KEY(test_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Test';

INSERT INTO test(test_name, test_description, test_value, test_price, test_active) VALUES
('Test 1', 'This this the test 1', 1, 1.11, 1),
('Test 2', 'This this the test 2', 2, 2.22, 0),
('Test 3', 'This this the test 3', 3, 3.33, 1),
('Test 4', 'This this the test 4', 4, 4.44, 0),
('Test 5', 'This this the test 5', 5, 5.55, 1),
('Test 6', 'This this the test 6', 6, 6.66, 0),
('Test 7', 'This this the test 7', 7, 7.77, 1),
('Test 8', 'This this the test 8', 8, 8.88, 0),
('Test 9', 'This this the test 9', 9, 9.99, 1);
```

Update the file `src/config/database.json` to set your database connection parameters.

Update the file `src/config/config.json` if you want to change application configuration (log and result row count).

Open the index page in a web browser.

Use user `#1` and API key `APIKeyForUser1`.

To run PHPunit to test, use `runtests.sh`.

## License

PhpRestMVC is licensed under the MIT License - see the LICENSE file for details.
