import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
const SsoCallbackController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SsoCallbackController.url(options),
    method: 'get',
})

SsoCallbackController.definition = {
    methods: ["get","head"],
    url: '/sso/callback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
SsoCallbackController.url = (options?: RouteQueryOptions) => {
    return SsoCallbackController.definition.url + queryParams(options)
}

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
SsoCallbackController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SsoCallbackController.url(options),
    method: 'get',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
SsoCallbackController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: SsoCallbackController.url(options),
    method: 'head',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
const SsoCallbackControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SsoCallbackController.url(options),
    method: 'get',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
SsoCallbackControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SsoCallbackController.url(options),
    method: 'get',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
SsoCallbackControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SsoCallbackController.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

SsoCallbackController.form = SsoCallbackControllerForm

export default SsoCallbackController