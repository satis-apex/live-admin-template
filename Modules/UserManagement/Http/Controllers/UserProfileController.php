<?php
namespace Modules\UserManagement\Http\Controllers;

use Inertia\Inertia;
use Modules\UserManagement\Models\UserInfo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Facades\Modules\UserManagement\Services\UserProfileService;

class UserProfileController extends Controller
{
    protected $routeName = '';

    public function __construct()
    {
        $this->routeName = 'userProfile';
        // $this->$implementMethodPermission('UserProfile');
    }

    public function index()
    {
        $userInfo = request()->user()->profile->only('avatar', 'contact', 'first_name', 'middle_name', 'last_name', 'gender', 'id');
        return Inertia::render(
            'UserManagement::UserProfile/Index',
            [
                'breadcrumb' => getBreadcrumb() ?: readable('UserProfile'),
                'userInfo' => $userInfo
            ]
        );
    }

    public function store()
    {
        try {
            UserProfileService::add();
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'User Info Added Successfully');
    }

    public function update(int $id)
    {
        try {
            UserProfileService::update($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'User Info Updated Successfully');
    }

    public function destroy(int $id)
    {
        try {
            UserProfileService::remove($id);
        } catch (\Exception $e) {
            return Redirect::route($this->routeName . '.index')->with('error', $e->getMessage());
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'User Info Deleted Successfully');
    }

    public function updateAvatar()
    {
        $userInfo = UserInfo::find(request()->user()->profile->id);
        if (request()->has('avatar')) {
            $userInfo->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            return Redirect::route($this->routeName . '.index')->with('success', 'Avatar Updated Successfully');
        }
        return Redirect::route($this->routeName . '.index')->with('success', 'Invalid Avatar Uploaded.');
    }
}
