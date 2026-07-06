import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\MedicalCaseController::index
* @see app/Http/Controllers/MedicalCaseController.php:21
* @route '/medical-cases'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/medical-cases',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MedicalCaseController::index
* @see app/Http/Controllers/MedicalCaseController.php:21
* @route '/medical-cases'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MedicalCaseController::index
* @see app/Http/Controllers/MedicalCaseController.php:21
* @route '/medical-cases'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MedicalCaseController::index
* @see app/Http/Controllers/MedicalCaseController.php:21
* @route '/medical-cases'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\MedicalCaseController::index
* @see app/Http/Controllers/MedicalCaseController.php:21
* @route '/medical-cases'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MedicalCaseController::index
* @see app/Http/Controllers/MedicalCaseController.php:21
* @route '/medical-cases'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MedicalCaseController::index
* @see app/Http/Controllers/MedicalCaseController.php:21
* @route '/medical-cases'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\MedicalCaseController::store
* @see app/Http/Controllers/MedicalCaseController.php:41
* @route '/medical-cases'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/medical-cases',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\MedicalCaseController::store
* @see app/Http/Controllers/MedicalCaseController.php:41
* @route '/medical-cases'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MedicalCaseController::store
* @see app/Http/Controllers/MedicalCaseController.php:41
* @route '/medical-cases'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MedicalCaseController::store
* @see app/Http/Controllers/MedicalCaseController.php:41
* @route '/medical-cases'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MedicalCaseController::store
* @see app/Http/Controllers/MedicalCaseController.php:41
* @route '/medical-cases'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\MedicalCaseController::show
* @see app/Http/Controllers/MedicalCaseController.php:83
* @route '/medical-cases/{medicalCase}'
*/
export const show = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/medical-cases/{medicalCase}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MedicalCaseController::show
* @see app/Http/Controllers/MedicalCaseController.php:83
* @route '/medical-cases/{medicalCase}'
*/
show.url = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { medicalCase: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { medicalCase: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            medicalCase: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        medicalCase: typeof args.medicalCase === 'object'
        ? args.medicalCase.id
        : args.medicalCase,
    }

    return show.definition.url
            .replace('{medicalCase}', parsedArgs.medicalCase.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MedicalCaseController::show
* @see app/Http/Controllers/MedicalCaseController.php:83
* @route '/medical-cases/{medicalCase}'
*/
show.get = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MedicalCaseController::show
* @see app/Http/Controllers/MedicalCaseController.php:83
* @route '/medical-cases/{medicalCase}'
*/
show.head = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\MedicalCaseController::show
* @see app/Http/Controllers/MedicalCaseController.php:83
* @route '/medical-cases/{medicalCase}'
*/
const showForm = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MedicalCaseController::show
* @see app/Http/Controllers/MedicalCaseController.php:83
* @route '/medical-cases/{medicalCase}'
*/
showForm.get = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MedicalCaseController::show
* @see app/Http/Controllers/MedicalCaseController.php:83
* @route '/medical-cases/{medicalCase}'
*/
showForm.head = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\MedicalCaseController::update
* @see app/Http/Controllers/MedicalCaseController.php:132
* @route '/medical-cases/{medicalCase}'
*/
export const update = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/medical-cases/{medicalCase}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\MedicalCaseController::update
* @see app/Http/Controllers/MedicalCaseController.php:132
* @route '/medical-cases/{medicalCase}'
*/
update.url = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { medicalCase: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { medicalCase: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            medicalCase: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        medicalCase: typeof args.medicalCase === 'object'
        ? args.medicalCase.id
        : args.medicalCase,
    }

    return update.definition.url
            .replace('{medicalCase}', parsedArgs.medicalCase.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MedicalCaseController::update
* @see app/Http/Controllers/MedicalCaseController.php:132
* @route '/medical-cases/{medicalCase}'
*/
update.put = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\MedicalCaseController::update
* @see app/Http/Controllers/MedicalCaseController.php:132
* @route '/medical-cases/{medicalCase}'
*/
const updateForm = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MedicalCaseController::update
* @see app/Http/Controllers/MedicalCaseController.php:132
* @route '/medical-cases/{medicalCase}'
*/
updateForm.put = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

const MedicalCaseController = { index, store, show, update }

export default MedicalCaseController