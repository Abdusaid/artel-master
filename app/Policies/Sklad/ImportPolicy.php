<?php

namespace App\Policies\Sklad;

use App\User;
use App\Import;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImportPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
    }
    /**
     * Determine whether the user can view the import.
     *
     * @param  \App\User  $user
     * @param  \App\Import  $import
     * @return mixed
     */
    public function view(User $user, Import $import)
    {
        //
    }

    /**
     * Determine whether the user can create imports.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the import.
     *
     * @param  \App\User  $user
     * @param  \App\Import  $import
     * @return mixed
     */
    public function update(User $user, Import $import)
    {
//        if($import->rawFirm()->exports()->where('serial_number', $this->serial_number)->exists())
//            return false;
        if(Carbon::parse($import->created_at, "Asia/Tashkent")->diffInDays(Carbon::now()) > 10)
            return false;
        
        return true;
    }

    /**
     * Determine whether the user can delete the import.
     *
     * @param  \App\User  $user
     * @param  \App\Import  $import
     * @return mixed
     */
    public function delete(User $user, Import $import)
    {
//        if($import->rawFirm()->exports()->where('serial_number', $this->serial_number)->exists())
//            return false;
        if(Carbon::parse($import->created_at, "Asia/Tashkent")->diffInDays(Carbon::now()) > 10)
            return false;
        
        return true;
    }

    /**
     * Determine whether the user can restore the import.
     *
     * @param  \App\User  $user
     * @param  \App\Import  $import
     * @return mixed
     */
    public function restore(User $user, Import $import)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the import.
     *
     * @param  \App\User  $user
     * @param  \App\Import  $import
     * @return mixed
     */
    public function forceDelete(User $user, Import $import)
    {
        //
    }

    public function changeStatus(User $user, Import $import)
    {
        if(Carbon::parse($import->created_at, "Asia/Tashkent")->diffInDays(Carbon::now()) > 10)
            return false;

        return true;
    }
}
