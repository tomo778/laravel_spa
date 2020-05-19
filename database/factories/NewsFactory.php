<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\News;
use Faker\Generator as Faker;

$factory->define(News::class, function (Faker $faker) {
    return [
        'title' => $faker->realText($maxNbChars = 10, $indexSize = 1),
        'text' => $faker->realText($maxNbChars = 50, $indexSize = 1),
        //'price' => $faker->numberBetween($min = 600, $max = 4000),
    ];
});