<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Ticket;
use Faker\Generator as Faker;

$factory->define(Ticket::class, function (Faker $faker) {
    return [
        'officer_id' => factory(\App\User::class),
        'issued_at' => $faker->dateTime(),
        'reg_no' => $faker->word,
        'front_image' => $faker->sha256,
        'rear_image' => $faker->sha256,
        'dash_image' => $faker->sha256,
        'location_image' => $faker->sha256,
        'gps_lat' => $faker->randomFloat(7, 0, 999.9999999),
        'gps_lon' => $faker->randomFloat(7, 0, 999.9999999),
        'dvla_req_sent' => $faker->boolean,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'address1' => $faker->streetAddress,
        'address2' => $faker->secondaryAddress,
        'address3' => $faker->word,
        'town' => $faker->word,
        'postcode' => $faker->postcode,
        'notice_sent' => $faker->boolean,
        'reminder_sent' => $faker->boolean,
        'client_access_code' => $faker->word,
        'payment_made_amt' => $faker->randomFloat(2, 0, 9.99),
        'payment_made_date' => $faker->date(),
    ];
});
