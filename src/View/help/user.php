<?php declare(strict_types=1);
/*
 * View/help/user.php
 *
 * Help file for User object
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */
?>
<?= $env::getName() ?> help for User

Access to this object is restricted to the current user, the one executing the request (actually user #<?= $router::getUser() ?>).

Create and delete actions (POST and DELETE) are disabled for user.

User must be active to use this API.

Fields availables for update:

- usr_firstname (string), the user's first name.
- usr_lastname (string), the user's last name.
- usr_description (string), the user's description.
- usr_active (boolean), the user's active flag.

Nota: use integer values for boolean fields (0/1).
