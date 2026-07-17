<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Vite Build Freshness Guard
|--------------------------------------------------------------------------
|
| A stale or missing public/build/manifest.json makes Inertia::render() throw
| "Unable to locate file in Vite manifest", which Laravel's exception handler
| turns into a full HTML error page instead of a valid Inertia response — this
| reads exactly like a controller/auth bug. CI always runs `npm run build`
| before the test suite, so this only guards local runs. See ARBO-88.
|
*/

(function (): void {
    $manifest = __DIR__.'/../public/build/manifest.json';

    if (! file_exists($manifest)) {
        throw new RuntimeException('Vite build is missing (public/build/manifest.json not found). Run `npm run build` before running tests — otherwise Inertia::render() fails with a misleading error. See ARBO-88.');
    }

    $manifestTime = filemtime($manifest);
    $sourceFiles = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__.'/../resources/js', FilesystemIterator::SKIP_DOTS)
    );

    foreach ($sourceFiles as $file) {
        if ($file->getMTime() > $manifestTime) {
            throw new RuntimeException('Vite build is stale (resources/js has changes newer than public/build/manifest.json). Run `npm run build` before running tests — otherwise Inertia::render() fails with a misleading error. See ARBO-88.');
        }
    }
})();

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}
