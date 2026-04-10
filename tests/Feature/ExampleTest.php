<?php

use Illuminate\Foundation\Auth\User;
use Orchestra\Testbench\Factories\UserFactory;

beforeEach(function () {
    config(['advanced-chat.user_model' => User::class]);
});

it('returns a successful response', function () {
    $this
        ->actingAs(UserFactory::new()->makeOne())
        // ->withSession(['banned' => false])
        ->get('/advanced-chat/api/users')
        ->assertStatus(200);
});
