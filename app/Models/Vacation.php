<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Vacation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Keeps the dates format.
     */
    public const DATES_FORMAT = 'Y-m-d\TH:i:s';

    /**
     * @var string[]
     */
    protected $fillable = [
        'start',
        'end',
        'price',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    /**
     * Filters the query based on the provided conditions.
     *
     * @param EloquentBuilder|QueryBuilder $builder
     * @param string $field
     * @param array $conditions
     *
     * @return EloquentBuilder|QueryBuilder
     */
    public function scopeFilter(EloquentBuilder|QueryBuilder $builder, string $field, array $conditions): EloquentBuilder|QueryBuilder
    {
        $eq = $conditions['eq'] ?? null;
        if ($eq) {
            $builder->where($field, $eq);

            return $builder;
        }

        foreach ($conditions as $condition => $value) {
            switch ($condition) {
                case 'lte':
                    $builder->where($field, '<=', $value);
                    break;
                case 'gte':
                    $builder->where($field, '>=', $value);
                    break;
            }
        }

        return $builder;
    }
}
