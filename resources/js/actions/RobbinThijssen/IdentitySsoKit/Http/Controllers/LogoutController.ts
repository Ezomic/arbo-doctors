import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\LogoutController::__invoke
* @see Users/robbinthijssen/Herd/identity-sso-kit/src/Http/Controllers/LogoutController.php:24
* @route '/logout'
*/
const LogoutController = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: LogoutController.url(options),
    method: 'post',
})

LogoutController.definition = {
    methods: ["post"],
    url: '/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\LogoutController::__invoke
* @see Users/robbinthijssen/Herd/identity-sso-kit/src/Http/Controllers/LogoutController.php:24
* @route '/logout'
*/
LogoutController.url = (options?: RouteQueryOptions) => {
    return LogoutController.definition.url + queryParams(options)
}

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\LogoutController::__invoke
* @see Users/robbinthijssen/Herd/identity-sso-kit/src/Http/Controllers/LogoutController.php:24
* @route '/logout'
*/
LogoutController.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: LogoutController.url(options),
    method: 'post',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\LogoutController::__invoke
* @see Users/robbinthijssen/Herd/identity-sso-kit/src/Http/Controllers/LogoutController.php:24
* @route '/logout'
*/
const LogoutControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: LogoutController.url(options),
    method: 'post',
})

/**
* @see \RobbinThijssen\IdentitySsoKit\Http\Controllers\LogoutController::__invoke
* @see Users/robbinthijssen/Herd/identity-sso-kit/src/Http/Controllers/LogoutController.php:24
* @route '/logout'
*/
LogoutControllerForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: LogoutController.url(options),
    method: 'post',
})

LogoutController.form = LogoutControllerForm

export default LogoutController