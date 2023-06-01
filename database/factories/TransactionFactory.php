<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
    	'ref_req_no' => Str::random(8),
        'source_app' => 'OREM',
        'source_url' => $faker->title(),
        'details' => 'Travel Order',
        'requestor' => 'jsmonton',
        'status' => 'PENDING'
    ];
});
