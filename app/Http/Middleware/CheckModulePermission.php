<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Maps a resource route action to a module permission.
 *
 * Usage: ->middleware('module:orders')
 *   index           => orders.view
 *   show            => orders.show
 *   create / store  => orders.create
 *   edit / update   => orders.edit
 *   destroy         => orders.delete
 */
class CheckModulePermission
{
    private const ACTION_MAP = [
        'index'   => 'view',
        'show'    => 'show',
        'create'  => 'create',
        'store'   => 'create',
        'edit'    => 'edit',
        'update'  => 'edit',
        'destroy' => 'delete',
    ];

    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = $request->user();

        abort_unless($user, 401);

        $method = $request->route()?->getActionMethod();
        $action = self::ACTION_MAP[$method] ?? 'view';

        abort_unless(
            $user->can("{$module}.{$action}"),
            403,
            'You do not have permission to perform this action.'
        );

        return $next($request);
    }
}
