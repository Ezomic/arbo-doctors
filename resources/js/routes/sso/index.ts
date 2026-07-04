import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
export const callback = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: callback.url(options),
    method: 'get',
})

callback.definition = {
    methods: ["get","head"],
    url: '/sso/callback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
callback.url = (options?: RouteQueryOptions) => {
    return callback.definition.url + queryParams(options)
}

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
callback.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: callback.url(options),
    method: 'get',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
callback.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: callback.url(options),
    method: 'head',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
const callbackForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: callback.url(options),
    method: 'get',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
callbackForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: callback.url(options),
    method: 'get',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/SsoCallbackController.php:26
* @route '/sso/callback'
*/
callbackForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: callback.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

callback.form = callbackForm

const sso = {
    callback: Object.assign(callback, callback),
}

export default sso