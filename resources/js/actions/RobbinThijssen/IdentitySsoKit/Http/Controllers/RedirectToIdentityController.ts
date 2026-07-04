import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\RedirectToIdentityController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/RedirectToIdentityController.php:34
* @route '/login'
*/
const RedirectToIdentityController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectToIdentityController.url(options),
    method: 'get',
})

RedirectToIdentityController.definition = {
    methods: ["get","head"],
    url: '/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\RedirectToIdentityController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/RedirectToIdentityController.php:34
* @route '/login'
*/
RedirectToIdentityController.url = (options?: RouteQueryOptions) => {
    return RedirectToIdentityController.definition.url + queryParams(options)
}

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\RedirectToIdentityController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/RedirectToIdentityController.php:34
* @route '/login'
*/
RedirectToIdentityController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectToIdentityController.url(options),
    method: 'get',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\RedirectToIdentityController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/RedirectToIdentityController.php:34
* @route '/login'
*/
RedirectToIdentityController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectToIdentityController.url(options),
    method: 'head',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\RedirectToIdentityController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/RedirectToIdentityController.php:34
* @route '/login'
*/
const RedirectToIdentityControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectToIdentityController.url(options),
    method: 'get',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\RedirectToIdentityController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/RedirectToIdentityController.php:34
* @route '/login'
*/
RedirectToIdentityControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectToIdentityController.url(options),
    method: 'get',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\RedirectToIdentityController::__invoke
* @see Users/robbinthijssen/Herd/arbo-saas/identity-sso-kit/src/Http/Controllers/RedirectToIdentityController.php:34
* @route '/login'
*/
RedirectToIdentityControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectToIdentityController.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectToIdentityController.form = RedirectToIdentityControllerForm

export default RedirectToIdentityController