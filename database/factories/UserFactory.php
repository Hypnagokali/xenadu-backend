<?php
/**
 * Creating standard user
 * Stefan, Lisa, Gast und Max Mustermann
 */


/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;

$factory->define(User::class, function () {
    $password = password_hash('itsmemygod', PASSWORD_BCRYPT);
    return [
        'name' => 'Stefan Simon',
        'email' => 'stefan.simon@xenadu.de',
        'password' => $password,
    ];
});

$factory->define(User::class, function () {
    $password = password_hash('secret', PASSWORD_BCRYPT);
    return [
        'name' => 'Max Mustermann',
        'email' => 'max@mustermann.de',
        'password' => $password,
    ];
});

$factory->define(User::class, function () {
    $password = password_hash('tertia333', PASSWORD_BCRYPT);
    return [
        'name' => 'Lisa S.',
        'email' => 'lisa@stremlau.de',
        'password' => $password,
    ];
});

$factory->define(User::class, function () {
    $password = password_hash('gast1', PASSWORD_BCRYPT);
    return [
        'name' => 'Gast',
        'email' => 'gast@xenadu.de',
        'password' => $password,
    ];
});
