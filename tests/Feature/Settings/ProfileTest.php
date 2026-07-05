<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('the profile page links out to Identity for account security management', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/Profile')
            ->where('identityBaseUrl', config('sso.identity_base_url')),
        );
});
