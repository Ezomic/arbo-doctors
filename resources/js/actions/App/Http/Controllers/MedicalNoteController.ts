import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\MedicalNoteController::store
* @see app/Http/Controllers/MedicalNoteController.php:15
* @route '/medical-cases/{medicalCase}/notes'
*/
export const store = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/medical-cases/{medicalCase}/notes',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\MedicalNoteController::store
* @see app/Http/Controllers/MedicalNoteController.php:15
* @route '/medical-cases/{medicalCase}/notes'
*/
store.url = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{medicalCase}', parsedArgs.medicalCase.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MedicalNoteController::store
* @see app/Http/Controllers/MedicalNoteController.php:15
* @route '/medical-cases/{medicalCase}/notes'
*/
store.post = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MedicalNoteController::store
* @see app/Http/Controllers/MedicalNoteController.php:15
* @route '/medical-cases/{medicalCase}/notes'
*/
const storeForm = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MedicalNoteController::store
* @see app/Http/Controllers/MedicalNoteController.php:15
* @route '/medical-cases/{medicalCase}/notes'
*/
storeForm.post = (args: { medicalCase: string | { id: string } } | [medicalCase: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\MedicalNoteController::update
* @see app/Http/Controllers/MedicalNoteController.php:35
* @route '/medical-cases/{medicalCase}/notes/{medicalNote}'
*/
export const update = (args: { medicalCase: string | { id: string }, medicalNote: string | { id: string } } | [medicalCase: string | { id: string }, medicalNote: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/medical-cases/{medicalCase}/notes/{medicalNote}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\MedicalNoteController::update
* @see app/Http/Controllers/MedicalNoteController.php:35
* @route '/medical-cases/{medicalCase}/notes/{medicalNote}'
*/
update.url = (args: { medicalCase: string | { id: string }, medicalNote: string | { id: string } } | [medicalCase: string | { id: string }, medicalNote: string | { id: string } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            medicalCase: args[0],
            medicalNote: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        medicalCase: typeof args.medicalCase === 'object'
        ? args.medicalCase.id
        : args.medicalCase,
        medicalNote: typeof args.medicalNote === 'object'
        ? args.medicalNote.id
        : args.medicalNote,
    }

    return update.definition.url
            .replace('{medicalCase}', parsedArgs.medicalCase.toString())
            .replace('{medicalNote}', parsedArgs.medicalNote.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MedicalNoteController::update
* @see app/Http/Controllers/MedicalNoteController.php:35
* @route '/medical-cases/{medicalCase}/notes/{medicalNote}'
*/
update.put = (args: { medicalCase: string | { id: string }, medicalNote: string | { id: string } } | [medicalCase: string | { id: string }, medicalNote: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\MedicalNoteController::update
* @see app/Http/Controllers/MedicalNoteController.php:35
* @route '/medical-cases/{medicalCase}/notes/{medicalNote}'
*/
const updateForm = (args: { medicalCase: string | { id: string }, medicalNote: string | { id: string } } | [medicalCase: string | { id: string }, medicalNote: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MedicalNoteController::update
* @see app/Http/Controllers/MedicalNoteController.php:35
* @route '/medical-cases/{medicalCase}/notes/{medicalNote}'
*/
updateForm.put = (args: { medicalCase: string | { id: string }, medicalNote: string | { id: string } } | [medicalCase: string | { id: string }, medicalNote: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\MedicalNoteController::destroy
* @see app/Http/Controllers/MedicalNoteController.php:48
* @route '/medical-cases/{medicalCase}/notes/{medicalNote}'
*/
export const destroy = (args: { medicalCase: string | { id: string }, medicalNote: string | { id: string } } | [medicalCase: string | { id: string }, medicalNote: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/medical-cases/{medicalCase}/notes/{medicalNote}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\MedicalNoteController::destroy
* @see app/Http/Controllers/MedicalNoteController.php:48
* @route '/medical-cases/{medicalCase}/notes/{medicalNote}'
*/
destroy.url = (args: { medicalCase: string | { id: string }, medicalNote: string | { id: string } } | [medicalCase: string | { id: string }, medicalNote: string | { id: string } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            medicalCase: args[0],
            medicalNote: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        medicalCase: typeof args.medicalCase === 'object'
        ? args.medicalCase.id
        : args.medicalCase,
        medicalNote: typeof args.medicalNote === 'object'
        ? args.medicalNote.id
        : args.medicalNote,
    }

    return destroy.definition.url
            .replace('{medicalCase}', parsedArgs.medicalCase.toString())
            .replace('{medicalNote}', parsedArgs.medicalNote.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MedicalNoteController::destroy
* @see app/Http/Controllers/MedicalNoteController.php:48
* @route '/medical-cases/{medicalCase}/notes/{medicalNote}'
*/
destroy.delete = (args: { medicalCase: string | { id: string }, medicalNote: string | { id: string } } | [medicalCase: string | { id: string }, medicalNote: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\MedicalNoteController::destroy
* @see app/Http/Controllers/MedicalNoteController.php:48
* @route '/medical-cases/{medicalCase}/notes/{medicalNote}'
*/
const destroyForm = (args: { medicalCase: string | { id: string }, medicalNote: string | { id: string } } | [medicalCase: string | { id: string }, medicalNote: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MedicalNoteController::destroy
* @see app/Http/Controllers/MedicalNoteController.php:48
* @route '/medical-cases/{medicalCase}/notes/{medicalNote}'
*/
destroyForm.delete = (args: { medicalCase: string | { id: string }, medicalNote: string | { id: string } } | [medicalCase: string | { id: string }, medicalNote: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const MedicalNoteController = { store, update, destroy }

export default MedicalNoteController