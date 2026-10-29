<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReferenceService
{
    public function generateUniqueReference(Model $model, string $field = 'reference', int $length = 8): string
    {
        do {
            $reference = Str::lower(Str::random($length));
            $exists = $model->newQuery()->where($field, $reference)->exists();
        } while ($exists);

        return $reference;
    }
}
