<?php
namespace App\Http\Traits;

trait MethodPermissionTrait
{
    public function implementMethodPermission($permissionFor) :void
    {
        $this->middleware(
            'permission:'
                . $permissionFor . '-access|'
                . $permissionFor . '-create|'
                . $permissionFor . '-edit|'
                . $permissionFor . '-delete',
            ['only' => ['index', 'show', 'listAll']]
        );
        $this->middleware('permission:' . $permissionFor . '-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:' . $permissionFor . '-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:' . $permissionFor . '-delete', ['only' => ['delete']]);
    }
}
