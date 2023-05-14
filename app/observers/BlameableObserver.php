<?php

namespace App\Observers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Blameable;
use File;

class BlameableObserver
{
    public function creating(Model $model)
    {
        if (Session::has('useractive')){
            $model->created_by = Session::get('useractive')->id;
        }
    }

    public function updating(Model $model)
    {
        if (Session::has('useractive')){
            $model->updated_by = Session::get('useractive')->id;
        }
    }
}
