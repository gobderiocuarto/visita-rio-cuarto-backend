<?php

use Faker\Generator as Faker;

$factory->define(App\Street::class, function (Faker $faker) {
    $name = $faker->unique()->streetName();

    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});