<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\EmployeeAddress
 *
 * @property int $id
 * @property int $employee_id
 * @property string $residence_country
 * @property string $residence_zip_code
 * @property string $residence_city
 * @property string $residence_street
 * @property string $residence_building_number
 * @property string|null $residence_apartment_number
 * @property int $different_correspondence_address
 * @property string|null $correspondence_country
 * @property string|null $correspondence_zip_code
 * @property string|null $correspondence_city
 * @property string|null $correspondence_street
 * @property string|null $correspondence_building_number
 * @property string|null $correspondence_apartment_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee $employee
 *
 * @method static \Database\Factories\EmployeeAddressFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAddress query()
 *
 * @mixin \Eloquent
 */
class EmployeeAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'residence_country',
        'residence_zip_code',
        'residence_city',
        'residence_street',
        'residence_building_number',
        'residence_apartment_number',
        'different_correspondence_address',
        'correspondence_country',
        'correspondence_zip_code',
        'correspondence_city',
        'correspondence_street',
        'correspondence_building_number',
        'correspondence_apartment_number',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
