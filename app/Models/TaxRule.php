<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TaxRule
 *
 * @property int $id
 * @property string $name
 * @property string|null $country
 * @property float $rate
 * @property bool $is_active
 */
class TaxRule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'country',
        'rate',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rate'      => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
