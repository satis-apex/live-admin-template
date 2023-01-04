<?php
namespace Modules\UserManagement\Database\Seeders;

use Illuminate\Support\Str;
use Modules\UserManagement\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        $users->each(function ($user) {
            $user->notifications()->create([
                'id' => Str::uuid()->toString(),
                'type' => 'App\Notifications\NewComment',
                'data' => [
                    'title' => 'A new comment',
                    'message' => 'A new comment has been added to one of your posts.'
                ]
            ]);
        });
    }
}
