<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRegionHierarchy implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $parentId;
    protected $type;

    public function __construct($parentId, $type)
    {
        $this->parentId = $parentId;
        $this->type = $type; // 'city' or 'district'
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->type === 'city') {
            $exists = \App\Models\City::where('id', $value)
                ->where('province_id', $this->parentId)
                ->exists();
            if (!$exists) $fail("Kota/Kabupaten yang dipilih tidak valid untuk provinsi tersebut.");
        }

        if ($this->type === 'district') {
            $exists = \App\Models\District::where('id', $value)
                ->where('city_id', $this->parentId)
                ->exists();
            if (!$exists) $fail("Kecamatan yang dipilih tidak valid untuk kota/kabupaten tersebut.");
        }
    }
}
