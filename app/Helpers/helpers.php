<?php

use App\Models\Clinic;
use Illuminate\Support\Facades\Auth;

if (! function_exists('tenant')) {
    function tenant(): ?Clinic
    {
        return Auth::user()?->clinic;
    }
}
