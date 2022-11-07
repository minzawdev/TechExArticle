<?php

namespace App\Traits;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait CommonTrait
{
    protected function searchArray($needle, $haystack)
    {
        return preg_grep("/(\w*)" . preg_quote($needle) . "(\w*)/i", $haystack);
    }

    protected function decrypt($value, $serialized = true)
    {
        return decrypt($value, $serialized);
    }
}
