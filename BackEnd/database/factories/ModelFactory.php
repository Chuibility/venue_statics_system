<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->sentence,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'wechat_openid' => $faker->randomNumber(),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Act::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence,
        'creator_uid' => $faker->randomNumber(),
        'people1_uid' => $faker->randomNumber(),
        'people2_uid' => $faker->randomNumber(),
        'people3_uid' => $faker->randomNumber(),
        'from' => $faker->sentence,
        'to' => $faker->sentence,
        'expectedNumber' => $faker->randomDigit,
        'state' => $faker->numberBetween(0, 1),
    ];
});
