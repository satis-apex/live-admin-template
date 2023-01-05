<?php

use App\Activators\Module;
use Illuminate\Support\Str;
use Modules\UserManagement\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Modules\MenuManagement\Models\MenuLink;
use Illuminate\Support\Facades\Storage;
use Modules\AppManagement\Models\ApplicationInfo;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

if (!\function_exists('executeMax')) {
    function executeMax($limit = 10)
    {
        set_time_limit($limit);
    }
}
if (!\function_exists('suffix')) {
    function suffix($number)
    {
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number . 'th';
        } else {
            return $number . $ends[$number % 10];
        }
    }
}
if (!\function_exists('getPercent')) {
    function getPercent($obtain, $total)
    {
        return round(($obtain / $total) * 100, 2);
    }
}
if (!\function_exists('getAppInfo')) {
    function getAppInfo($key = '')
    {
        $appInfo = ApplicationInfo::first();
        if ($key != '') {
            return $appInfo->$key;
        }
        return $appInfo;
    }
}
if (!\function_exists('file_name_info')) {
    function file_name_info($value = '')
    {
        $all_info = explode('/', $value);
        $info['file_name'] = end($all_info);
        $info['name'] = current(explode('.', $info['file_name']));
        return $info;
    }
}
if (!\function_exists('make_file')) {
    function make_file($filepath, $information)
    {
        Storage::disk('local')->put($filepath, $information);
    }
}
if (!\function_exists('read_file')) {
    function read_file($filepath)
    {
        try {
            $contents = File::get($filepath);
            return $contents;
        } catch (Illuminate\Contracts\Filesystem\FileNotFoundException $exception) {
            throw new Exception('File Not Found');
        }
    }
}
if (!\function_exists('arrayFlipMapping')) {
    function arrayFlipMapping($source, $options)
    {
        $options = array_flip(explode(',', $options));
        return (array_intersect_key($source, $options));
    }
}
if (!\function_exists('objectToArray')) {
    function objectToArray($object)
    {
        if (!\is_object($object) && !\is_array($object)) {
            return $object;
        }
        return array_map('objectToArray', (array) $object);
    }
}
if (!\function_exists('arrayKeyFlatten')) {
    function arrayKeyFlatten($MenuArray, $keys)
    {
        $output = [];
        if (\is_array($MenuArray)) {
            array_walk_recursive($MenuArray, function ($value, $key) use (&$output, &$keys) {
                if ($key === $keys) {
                    $output[] = $value;
                }
            });
            return $output;
        }
        return $output;
    }
}
if (!\function_exists('previousRoute')) {
    /**
     * Generate a route name for the previous request.
     *
     * @return string|null
     */
    function previousRoute()
    {
        $previousRequest = app('request')->create(app('url')->previous());
        try {
            $routeName = app('router')->getRoutes()->match($previousRequest)->getName();
        } catch (NotFoundHttpException $exception) {
            return null;
        }
        return $routeName;
    }
}
if (!\function_exists('glue')) {
    function glue($array, $glue = ' ')
    {
        return implode($glue, array_filter($array, 'strlen'));
    }
}
/**
 * Dynamically set the database provided option temporarily
 *
 * @param mixed $option
 * database config option
 *
 * @param $state
 * database config state
 *
 * @return bool true if provided setting is implemented,
 * false otherwise
 */
if (!\function_exists('dbDynamic')) {
    function dbDynamic($option, $state)
    {
        config([$option => $state]);
        DB::purge(env('DB_CONNECTION'));
        return DB::reconnect(env('DB_CONNECTION'));
    }
}
if (!\function_exists('dumpSql')) {
    function dumpSql($query)
    {
        return dd(Str::replaceArray('?', $query->getBindings(), $query->toSql()));
    }
}
if (!\function_exists('getSql')) {
    function getSql($query)
    {
        return Str::replaceArray('?', $query->getBindings(), $query->toSql());
    }
}
if (!\function_exists('arrayFilter')) {
    function arrayFilter($dataArray, $filterKey)
    {
        $filteredArray = [];
        foreach ($dataArray as $value) {
            $filteredArray[] = $value[$filterKey];
        }
        return $filteredArray;
    }
}
if (!\function_exists('urlExists')) {
    function urlExists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }
}
if (!\function_exists('fileExists')) {
    function fileExists($file)
    {
        $str = str_replace(env('APP_URL') . '/', '', $file);
        return file_exists($str);
    }
}
if (!\function_exists('resetAutoIncrement')) {
    function resetAutoIncrement($db_table)
    {
        DB::unprepared("SET  @num := 0; UPDATE {$db_table} SET id = @num := (@num+1); ALTER TABLE {$db_table} AUTO_INCREMENT =1;");
    }
}
if (!\function_exists('update_env')) {
    function update_env($data = []): void
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            foreach ($data as $key => $value) {
                file_put_contents($path, str_replace(
                    $key . '=' . env($key),
                    $key . '=' . $value,
                    file_get_contents($path)
                ));
            }
        }
    }
}
if (!\function_exists('getDistrict')) {
    function getDistrict()
    {
        $districtList = File::get(storage_path('app/districts.json'));
        return json_encode(json_decode($districtList));
    }
}
if (!\function_exists('getCountry')) {
    function getCountry()
    {
        $countryList = File::get(storage_path('app/countries.json'));
        return json_encode(json_decode($countryList));
    }
}

if (!\function_exists('generateUsername')) {
    function generateUsername($userName)
    {
        $username = $userName;
        $i = 0;
        while (User::whereUsername($username)->exists()) {
            $i++;
            $username = $userName . $i;
        }
        return $username;
    }
}

if (!\function_exists('sendMail')) {
    function sendMail($to_name, $to_email, $subject, $templateData)
    {
        Mail::send('emails.mail', $templateData, function ($message) use ($to_name, $to_email, $subject) {
            $message->to($to_email, $to_name)->subject($subject);
            $message->from('No-Reply.amabuba@gmail.com', 'No-Reply');
        });
    }
}
if (!\function_exists('getBreadcrumb')) {
    function getBreadcrumb()
    {
        $routeName = Route::current()->getName();
        $menuLink = MenuLink::where('link', $routeName)->first();
        if ($menuLink != null) {
            return $menuLink->type == 'child' ? $menuLink->parentName . ' / ' . $menuLink->name : $menuLink->name;
        }
        return null;
    }
}

if (!\function_exists('readable')) {
    function readable($string)
    {
        $words = str_replace(['-', '_'], ' ', $string);
        $escapedCamel = strtolower(preg_replace('/(?<!^)[A-Z]/', ' $0', $words));
        return ucwords(
            preg_replace(
                '~(\s|\x{3164})+~u',
                ' ',
                preg_replace(
                    '~^[\s﻿]+|[\s﻿]+$~u',
                    '',
                    $escapedCamel
                )
            )
        );
    }
}

if (!\function_exists('isAuthorized')) {
    function isAuthorized($role)
    {
        $userRole = Auth::user()->roles[0]->name;
        if (\is_array($role)) {
            return \in_array(strtolower($userRole), array_map('strtolower', $role));
        }
        return strtolower($userRole) == strtolower($role);
    }
}

if (!\function_exists('getModuleRoutes')) {
    function getModuleRoutes($moduleName, $baseModulesPath = 'Modules')
    {
        $routeCollection = Route::getRoutes();
        $menuRoutes = [];

        $routeCollection = collect(Route::getRoutes())
        ->filter(
            fn ($item) => isset($item->action['namespace']) && Str::startsWith($item->action['namespace'], "{$baseModulesPath}\\{$moduleName}")
        )
        ->values()
        ->all();
        foreach ($routeCollection as $value) {
            if ($value->getActionMethod() == 'index' || $value->getActionMethod() == '__invoke') {
                $menuRoutes[] = $value->getName();
            }
        }
        return $menuRoutes;
    }
}
if (!\function_exists('getRoutesModule')) {
    function getRoutesModule($routeName)
    {
        $modules = Module::pluck('name')->all();
        $routesModule = '';
        foreach ($modules as $module) {
            $moduleRoutes = getModuleRoutes($module);
            foreach ($moduleRoutes as $moduleRoute) {
                if ($moduleRoute == $routeName) {
                    $routesModule = $module;
                    break 2;
                }
            }
        }
        return $routesModule;
    }
}
