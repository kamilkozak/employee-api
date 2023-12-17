<?php

namespace App\Models;

use App\Enums\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Employee
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string|null $phone_number
 * @property int|null $average_salary_last_year
 * @property Position $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\EmployeeAddress|null $employeeAddress
 *
 * @method static \Database\Factories\EmployeeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee query()
 *
 * @mixin \Eloquent
 */
class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'average_salary_last_year',
        'position',
    ];

    protected $casts = [
        'position' => Position::class,
    ];

    public function employeeAddress(): HasOne
    {
        return $this->hasOne(EmployeeAddress::class);
    }
}
