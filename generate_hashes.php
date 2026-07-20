<?php
echo 'Usuario 1 (12345): ' . password_hash('12345', PASSWORD_BCRYPT) . PHP_EOL;
echo 'Usuario 2 (password123): ' . password_hash('password123', PASSWORD_BCRYPT) . PHP_EOL;
?>
