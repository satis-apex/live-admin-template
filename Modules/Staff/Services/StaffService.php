<?php
namespace Modules\Staff\Services;

use Illuminate\Support\Str;
use Modules\Staff\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;

class StaffService
{
    public function __construct(Staff $staff)
    {
        $this->staff = $staff;
    }

    public function add()
    {
        DB::beginTransaction();
        try {
            $staff = $this->staff->create([
                'first_name' => request('firstName'),
                'middle_name' => request('middleName'),
                'last_name' => request('lastName'),
                'contact' => request('contact'),
                'gender' => request('gender'),
                'email' => request('email'),
                'address' => request('address'),
                'joined_date' => request('joinedDate'),
            ]);
            if (request()->has('userImage') && request('userImage') != null) {
                $staff->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }
            // If all good then store all the records to the relivent table
            if (request('accountCreate') == 'true' && request('role') != '') {
                $userRole = request('role');
                // $accountName = generateUsername(Str::lower($staff->first_name) . '.' . Str::lower($staff->last_name));
                $accountPassword = bin2hex(random_bytes(6));
                $user = $staff->account()->create([
                    'email' => $staff->email,
                    'password' => $accountPassword,
                ]);
                if ($user->wasRecentlyCreated) {
                    $to_name = $staff->fullName;
                    $to_email = $staff->email;
                    $templateData = [
                        'name' => $staff->fullName,
                        'email' => $staff->email,
                        'password' => $accountPassword
                    ];
                    $subject = 'User Account Credential';
                    Mail::send('staff::emails.mail', $templateData, function ($message) use ($to_name, $to_email, $subject) {
                        $message->to($to_email, $to_name)->subject($subject);
                        $message->from('No-Reply.liveadmin@gmail.com', 'No-Reply');
                    });
                }

                $user->assignRole($userRole);
            }

            DB::commit();
            return true;
        } catch (QueryException $e) {
            //database related exception
            DB::rollBack();
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            //general exception
            DB::rollBack();
            return throw new  \Exception($e->getMessage());
        }
    }

    public function massAdd()
    {
        try {
            $insertData = request('massRecord');
            foreach ($insertData as $row) {
                Staff::create($row);
            }
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function update($id)
    {
        $staffData = $this->staff->find($id);
        $previousUserRole = request('previousRole');
        $userRole = request('role');
        DB::beginTransaction();
        try {
            if ($previousUserRole != $userRole) {
                $staffData->account->removeRole($previousUserRole);
                $staffData->account->assignRole($userRole);
            }
            $staff = $staffData->update([
                'first_name' => request('first_name'),
                'middle_name' => request('middle_name'),
                'last_name' => request('last_name'),
                'contact' => request('contact'),
                'gender' => request('gender'),
                'email' => request('email'),
                'address' => request('address'),
            ]);

            if (request()->has('userImage') && request('userImage') != null) {
                $staffData->addMediaFromRequest('userImage')->toMediaCollection('avatar');
            }

            //If all good then store all the records to the relivent table

            DB::commit();
            return true;
        } catch (QueryException $e) {
            //database related exception
            DB::rollBack();
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            //general exception
            DB::rollBack();
            return throw new  \Exception($e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
            return Staff::destroy($id);
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function createAccount($staff)
    {
        try {
            DB::beginTransaction();
            $accountPassword = bin2hex(random_bytes(6));
            $user = $staff->account()->create([
                'email' => $staff->email,
                'password' => $accountPassword,
            ]);
            if ($user->wasRecentlyCreated) {
                $to_name = $staff->fullName;
                $to_email = $staff->email;
                $templateData = [
                    'name' => $staff->fullName,
                    'email' => $staff->email,
                    'password' => $accountPassword
                ];
                $subject = 'User Account Credential';

                Mail::send('staff::emails.mail', $templateData, function ($message) use ($to_name, $to_email, $subject) {
                    $message->to($to_email, $to_name)->subject($subject);
                    $message->from(env('MAIL_FROM_ADDRESS'), 'No-Reply');
                });
                DB::commit();
                return $user->assignRole('staff');

            }
        } catch (QueryException $e) {
            //database related exception
            DB::rollBack();
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            DB::rollBack();
            return throw new  \Exception($e->getMessage());
        }
    }

    public function resetPassword($staff)
    {
        try {
            DB::beginTransaction();
            $accountPassword = bin2hex(random_bytes(6));
            $user = $staff->account()->update([
                'password' => bcrypt($accountPassword),
            ]);
            if ($user) {
                $to_name = $staff->fullName;
                $to_email = $staff->email;
                $templateData = [
                    'name' => $staff->fullName,
                    'email' => $staff->email,
                    'password' => $accountPassword
                ];
                $subject = 'User Account Credential';
                Mail::send('staff::emails.mail', $templateData, function ($message) use ($to_name, $to_email, $subject) {
                    $message->to($to_email, $to_name)->subject($subject);
                    $message->from(env('MAIL_FROM_ADDRESS'), 'No-Reply');
                });
                DB::commit();
                return true;
            }
        } catch (QueryException $e) {
            //database related exception
            DB::rollBack();
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            DB::rollBack();
            return throw new  \Exception($e->getMessage());
        }
    }
}