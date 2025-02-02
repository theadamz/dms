<?php

use function Pest\Laravel\get;

test('redirect to sign in page', function () {
    $response = get('/');

    $response->assertRedirect(route('sign-in'))
        ->assertStatus(302);
});
