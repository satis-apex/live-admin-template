<?php
namespace App\Http\Middleware;

use Inertia\Middleware;
use App\Activators\Module;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\MenuManagement\Models\Menu;
use Modules\AppManagement\Models\ApplicationInfo;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'adminApp';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $flashMessage = $pageException = [];

        $menuObject = Menu::pluck('menu_list')->first();
        if (Session::get('pageException')) {
            $pageException = [
                'token' => md5(date('Y-m-d H:i:s')),
                'statusCode' => Session::get('pageException')
            ];
        }

        if ($message = Session::get('success')) {
            $flashMessage = ['message' => '', 'type' => 'success', 'title' => 'Success', 'hasHtML' => false];
            if (\is_array($message)) {
                $flashMessage = [...$flashMessage, ...$message];
            } else {
                $flashMessage['message'] = $message;
                $flashMessage['type'] = 'success';
                $flashMessage['title'] = 'Success';
                $flashMessage['hasHTML'] = false;
            }
        } elseif ($message = Session::get('info')) {
            $flashMessage = ['token' => md5(date('Y-m-d H:i:s')), 'message' => '', 'type' => 'info', 'title' => 'Info', 'hasHTML' => false];
            if (\is_array($message)) {
                $flashMessage = [...$flashMessage, ...$message];
            } else {
                $flashMessage['message'] = $message;
                $flashMessage['type'] = 'info';
                $flashMessage['title'] = 'Info';
                $flashMessage['hasHTML'] = false;
            }
        } elseif ($message = Session::get('warning')) {
            $flashMessage = ['message' => '', 'type' => 'warning', 'title' => 'Warning', 'hasHtML' => false];
            if (\is_array($message)) {
                $flashMessage = [...$flashMessage, ...$message];
            } else {
                $flashMessage['message'] = $message;
                $flashMessage['type'] = 'warning';
                $flashMessage['title'] = 'Warning';
                $flashMessage['hasHTML'] = false;
            }
        } elseif ($message = Session::get('error')) {
            $flashMessage = ['message' => '', 'type' => 'error', 'title' => 'Error', 'hasHtML' => false];
            if (\is_array($message)) {
                $flashMessage = [...$flashMessage, ...$message];
            } else {
                $flashMessage['message'] = $message;
                $flashMessage['type'] = 'error';
                $flashMessage['title'] = 'Error';
                $flashMessage['hasHTML'] = false;
            }
        }

        return array_merge(parent::share($request), [
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy())->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'auth' => fn () => $request->user()
                ?
                [
                    'user' => [
                        'id' => $request->user()->profile->id,
                        'firstName' => $request->user()->profile->first_name,
                        'lastName' => $request->user()->profile->last_name,
                        'email' => $request->user()->email,
                        'avatar' => $request->user()->profile->avatar,
                        'fullName' => $request->user()->profile->fullName,
                        'account' => [
                            'id' => $request->user()->id,
                            'role' => $request->user()->roles[0]->name
                        ]
                    ],
                    'isImpersonator' => session()->has('impersonator')
                ]

                : null,
            'app_info' => ApplicationInfo::first(),
            'app_menu' => fn () => $request->user()
                ? json_decode($menuObject)
                : null,
            'app_disabled_module' => fn () => $request->user()
                ? Module::where('is_active', '!=', '1')->pluck('name')->toArray()
                : null,
            'app_notification' => [
                'unread_count' => fn () => $request->user()
                ? $request->user()->unreadNotifications->count() : null,
                'all_notification' => fn () => $request->user()
                ? $request->user()->notifications : null
            ],
            'flash' => $flashMessage,
            'page_exception' => $pageException
        ]);
    }
}
