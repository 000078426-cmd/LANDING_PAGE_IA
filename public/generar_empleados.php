<?php
header('Content-Type: text/plain; charset=utf-8');

$empleados = [
    'empleado123' => 'empleado123',
    'empleado456' => 'empleado456',
];

foreach ($empleados as $texto => $nombre) {
    echo $nombre . ': ' . password_hash($texto, PASSWORD_BCRYPT) . PHP_EOL;
}
