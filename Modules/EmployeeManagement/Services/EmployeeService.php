<?php
namespace Modules\EmployeeManagement\Services;

use Illuminate\Support\Str;
use Modules\EmployeeManagement\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;

class EmployeeService
{
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function add()
    {
        DB::beginTransaction();
        try {
            $employee = $this->employee->create([
                'first_name' => request('first_name'),
                'middle_name' => request('middle_name'),
                'last_name' => request('last_name'),
                'phone' => request('phone'),
                'gender' => request('gender'),
                'email' => request('email'),
                'address' => request('address'),
                'joined_date' => request('joined_date'),
            ]);
            if (request()->has('user_image') && request('user_image') != null) {
                $employee->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }
            // If all good then store all the records to the relivent table
            if (request('accountCreate') == 'true' && request('role') != '') {
                $userRole = request('role');
                // $accountName = generateUsername(Str::lower($employee->first_name) . '.' . Str::lower($employee->last_name));
                $accountPassword = bin2hex(random_bytes(6));
                $user = $employee->account()->create([
                    'email' => $employee->email,
                    'password' => $accountPassword,
                ]);
                if ($user->wasRecentlyCreated) {
                    $to_name = $employee->fullName;
                    $to_email = $employee->email;
                    $templateData = [
                        'name' => $employee->fullName,
                        'email' => $employee->email,
                        'password' => $accountPassword
                    ];
                    $subject = 'User Account Credential';
                    Mail::send('EmployeeManagement::emails.mail', $templateData, function ($message) use ($to_name, $to_email, $subject) {
                        $message->to($to_email, $to_name)->subject($subject);
                        $message->from(env('MAIL_FROM_ADDRESS'), 'No-Reply');
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
                Employee::create($row);
            }
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function update($id)
    {
        $employeeData = $this->employee->find($id);
        $previousUserRole = request('previousRole');
        $userRole = request('role');
        DB::beginTransaction();
        try {
            if ($previousUserRole != '' && $previousUserRole != $userRole) {
                $employeeData->account->removeRole($previousUserRole);
                $employeeData->account->assignRole($userRole);
            }
            $employee = $employeeData->update([
                'first_name' => request('first_name'),
                'middle_name' => request('middle_name'),
                'last_name' => request('last_name'),
                'phone' => request('phone'),
                'gender' => request('gender'),
                'email' => request('email'),
                'address' => request('address'),
                'joined_date' => request('joined_date'),
            ]);

            if (request()->has('user_image') && request('user_image') != null) {
                $employeeData->addMediaFromRequest('user_image')->toMediaCollection('avatar');
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
            return Employee::destroy($id);
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function createAccount($employee)
    {
        try {
            DB::beginTransaction();
            $accountPassword = bin2hex(random_bytes(6));
            $user = $employee->account()->create([
                'email' => $employee->email,
                'password' => $accountPassword,
            ]);
            if ($user->wasRecentlyCreated) {
                $to_name = $employee->fullName;
                $to_email = $employee->email;
                $templateData = [
                    'name' => $employee->fullName,
                    'email' => $employee->email,
                    'password' => $accountPassword
                ];
                $subject = 'User Account Credential';

                Mail::send('employeemanagement::emails.mail', $templateData, function ($message) use ($to_name, $to_email, $subject) {
                    $message->to($to_email, $to_name)->subject($subject);
                    $message->from(env('MAIL_FROM_ADDRESS'), 'No-Reply');
                });
                DB::commit();
                return $user->assignRole('Employee');
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

    public function resetPassword($employee)
    {
        try {
            DB::beginTransaction();
            $accountPassword = bin2hex(random_bytes(6));
            $user = $employee->account()->update([
                'password' => bcrypt($accountPassword),
            ]);
            if ($user) {
                $to_name = $employee->fullName;
                $to_email = $employee->email;
                $templateData = [
                    'name' => $employee->fullName,
                    'email' => $employee->email,
                    'password' => $accountPassword
                ];
                $subject = 'User Account Credential';
                Mail::send('employeemanagement::emails.mail', $templateData, function ($message) use ($to_name, $to_email, $subject) {
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
