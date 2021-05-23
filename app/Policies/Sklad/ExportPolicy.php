<?php

namespace App\Policies\Sklad;

use App\User;
use App\Export;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExportPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
    }
    /**
     * Determine whether the user can view the export.
     *
     * @param  \App\User  $user
     * @param  \App\Export  $export
     * @return mixed
     */
    public function view(User $user, Export $export)
    {
        //
    }

    /**
     * Determine whether the user can create exports.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the export.
     *
     * @param  \App\User  $user
     * @param  \App\Export  $export
     * @return mixed
     */
    public function update(User $user, Export $export)
    {
        if(Carbon::parse($export->created_at, "Asia/Tashkent")->diffInDays(Carbon::now()) > 10)
            return false;
        
        return true;
    }

    /**
     * Determine whether the user can delete the export.
     *
     * @param  \App\User  $user
     * @param  \App\Export  $export
     * @return mixed
     */
    public function delete(User $user, Export $export)
    {
        if(Carbon::parse($export->created_at, "Asia/Tashkent")->diffInDays(Carbon::now()) > 10)
            return false;
        
        return true;
    }

    /**
     * Determine whether the user can restore the import.
     *
     * @param  \App\User  $user
     * @param  \App\Export  $export
     * @return mixed
     */
    public function restore(User $user, Export $export)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the export.
     *
     * @param  \App\User  $user
     * @param  \App\Export  $export
     * @return mixed
     */
    public function forceDelete(User $user, Export $export)
    {
        //
    }

}
