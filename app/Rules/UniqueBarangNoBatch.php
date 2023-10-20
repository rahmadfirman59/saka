<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueBarangNoBatch implements Rule
{
    protected $idsToExclude;
    
    public function __construct(array $idsToExclude)
    {
        $this->idsToExclude = $idsToExclude;
    }

    public function passes($attribute, $value)
    {
        $count = DB::table('barang')
            ->where('no_batch', $value)
            ->whereNotIn('id', $this->idsToExclude)
            ->count();
        
        return $count === 0;
    }

    public function message()
    {
        return 'The :attribute is not unique for the given IDs.';
    }

}
