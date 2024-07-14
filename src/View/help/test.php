<?php declare(strict_types=1);
/*
 * View/help/test.php
 *
 * Help file for Test object
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */
?>
<?= $env::getName() ?> help for Test

Fields availables for update:

- test_name (string), the test's name.
- test_description (string), the test's description.
- test_value (integer), the test's integer value.
- test_price (float), the test's float value.
- test_active (boolean), the test's active flag.

Nota: use integer values for boolean fields (0/1).
