<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use App\Models\ApplicationInfo;
use Hamcrest\Arrays\IsArray;
use Illuminate\Support\Facades\Session;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

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
        $flashMessage = [];
        if ($message = Session::get('success')) {
            $flashMessage = ['message' => '', 'type' => 'success', 'title' => 'Success', 'hasHtML' => false];
            if (is_array($message)) {
                $flashMessage = [...$flashMessage, ...$message];
            } else {
                $flashMessage['message'] = $message;
                $flashMessage['type'] = 'success';
                $flashMessage['title'] = 'Success';
                $flashMessage['hasHTML'] = false;
            }
        } else if ($message = Session::get('info')) {
            $flashMessage = ['message' => '', 'type' => 'info', 'title' => 'Info', 'hasHtML' => false];
            if (is_array($message)) {
                $flashMessage = [...$flashMessage, ...$message];
            } else {
                $flashMessage['message'] = $message;
                $flashMessage['type'] = 'info';
                $flashMessage['title'] = 'Info';
                $flashMessage['hasHTML'] = false;
            }
        } else if ($message = Session::get('warning')) {
            $flashMessage = ['message' => '', 'type' => 'warning', 'title' => 'Warning', 'hasHtML' => false];
            if (is_array($message)) {
                $flashMessage = [...$flashMessage, ...$message];
            } else {
                $flashMessage['message'] = $message;
                $flashMessage['type'] = 'warning';
                $flashMessage['title'] = 'Warning';
                $flashMessage['hasHTML'] = false;
            }
        } else if ($message = Session::get('error')) {
            $flashMessage = ['message' => '', 'type' => 'error', 'title' => 'Error', 'hasHtML' => false];
            if (is_array($message)) {
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
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'auth' => fn () => $request->user()
                ?
                [
                    "user" => [
                        "id" => $request->user()->profile->id,
                        "first_name" => $request->user()->profile->first_name,
                        "last_name" =>  $request->user()->profile->last_name,
                        "email" =>  $request->user()->email,
                        "avatar" => $request->user()->profile->avatar,
                        "fullName" => '',
                        'account' => [
                            'id' => $request->user()->id,
                            'role' => $request->user()->roles[0]->name
                        ]
                    ]
                ]

                : null,
            'app_info' => ApplicationInfo::first(),
            'app_menu' =>  fn () => $request->user()
                ? Menu::first('menu_list')
                : null,
            'flash' => $flashMessage
        ]);
    }
}
