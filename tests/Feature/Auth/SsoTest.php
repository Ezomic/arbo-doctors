<?php

use App\Models\Tenant;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

function ssoKeypair(): array
{
    $res = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
    openssl_pkey_export($res, $privateKey);
    $publicKey = openssl_pkey_get_details($res)['key'];

    return [$privateKey, $publicKey];
}

function ssoToken(string $privateKey, array $overrides = []): string
{
    $now = time();

    $payload = array_merge([
        'iss' => 'identity',
        'aud' => 'doctors',
        'sub' => (string) Str::uuid(),
        'iat' => $now,
        'exp' => $now + 120,
        'email' => 'derek@acme-arbo.test',
        'name' => 'Derek Doctor',
        'role' => 'doctor',
        'tenant_id' => (string) Str::uuid(),
        'tenant_name' => 'Acme Arbodienst',
        'apps' => [],
    ], $overrides);

    return JWT::encode($payload, $privateKey, 'RS256');
}

test('visiting login redirects to identity sso/authorize with app and redirect_to params', function () {
    $response = $this->get('/login');

    $response->assertRedirect();
    $location = $response->headers->get('Location');

    expect($location)->toContain(config('sso.identity_base_url').'/sso/authorize')
        ->and($location)->toContain('app=doctors')
        ->and($location)->toContain(urlencode(route('sso.callback')));
});

test('sso callback verifies the token and logs the user in', function () {
    [$privateKey, $publicKey] = ssoKeypair();
    Http::fake([config('sso.identity_base_url').'/.well-known/identity-public-key' => Http::response($publicKey)]);

    $token = ssoToken($privateKey);

    $response = $this->get('/sso/callback?token='.$token);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    expect(User::query()->where('email', 'derek@acme-arbo.test')->exists())->toBeTrue();
    expect(Tenant::query()->where('name', 'Acme Arbodienst')->exists())->toBeTrue();
});

test('sso callback rejects a token issued for a different app', function () {
    [$privateKey, $publicKey] = ssoKeypair();
    Http::fake([config('sso.identity_base_url').'/.well-known/identity-public-key' => Http::response($publicKey)]);

    $token = ssoToken($privateKey, ['aud' => 'case-officers']);

    $this->get('/sso/callback?token='.$token)->assertStatus(500);
    $this->assertGuest();
});

test('logging out destroys the local session and sends the browser on to end the Identity session too', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect(config('sso.identity_base_url').'/sso/logout');
    $this->assertGuest();
});
