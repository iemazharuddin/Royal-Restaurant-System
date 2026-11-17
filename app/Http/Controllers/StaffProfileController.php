<?php

namespace App\Http\Controllers;

use App\Models\StaffProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StaffProfileController extends AdminResourceController
{
    protected string $modelClass = StaffProfile::class;
    protected string $resource = 'staff';
    protected string $title = 'Staff Management';
    protected string $description = 'Manage employee records, positions and roles.';

    protected array $columns = [
        ['key' => 'employee_id', 'label' => 'Employee ID'],
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'position', 'label' => 'Position'],
        ['key' => 'role', 'label' => 'Role'],
        ['key' => 'status', 'label' => 'Status'],
    ];

    protected array $searchFields = [
        'employee_id',
        'name',
        'email',
        'position',
        'role',
    ];

    protected array $fields = [
        [
            'name' => 'employee_id',
            'label' => 'Employee ID (auto-generated if blank)',
            'type' => 'text',
        ],
        [
            'name' => 'name',
            'label' => 'Employee name',
            'type' => 'text',
            'required' => true,
        ],
        [
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
        ],
        [
            'name' => 'phone',
            'label' => 'Phone',
            'type' => 'tel',
        ],
        [
            'name' => 'position',
            'label' => 'Position',
            'type' => 'text',
            'required' => true,
        ],
        [
            'name' => 'role',
            'label' => 'Role',
            'type' => 'select',
            'required' => true,
            'options' => [
                'manager' => 'Manager',
                'chef' => 'Chef',
                'server' => 'Server',
                'cashier' => 'Cashier',
                'delivery' => 'Delivery',
                'staff' => 'Staff',
            ],
        ],
        [
            'name' => 'salary',
            'label' => 'Salary (Rs.)',
            'type' => 'number',
            'step' => '0.01',
        ],
        [
            'name' => 'joining_date',
            'label' => 'Joining date',
            'type' => 'date',
        ],
        [
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select',
            'required' => true,
            'options' => [
                'active' => 'Active',
                'inactive' => 'Inactive',
                'on_leave' => 'On leave',
            ],
        ],
    ];

    protected function rules(?Model $record): array
    {
        return [
            'employee_id' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('staff_profiles', 'employee_id')
                    ->ignore($record?->id),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('staff_profiles', 'email')
                    ->ignore($record?->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'position' => [
                'required',
                'string',
                'max:100',
            ],

            'role' => [
                'required',
                Rule::in([
                    'manager',
                    'chef',
                    'server',
                    'cashier',
                    'delivery',
                    'staff',
                ]),
            ],

            'salary' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'joining_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                    'on_leave',
                ]),
            ],
        ];
    }

    protected function prepareData(
        array $validated,
        Request $request,
        ?Model $record
    ): array {
        $validated = parent::prepareData($validated, $request, $record);

        if (blank($validated['employee_id'] ?? null)) {
            do {
                $employeeId = 'EMP-' . Str::upper(Str::random(8));
            } while (
                StaffProfile::where('employee_id', $employeeId)->exists()
            );

            $validated['employee_id'] = $employeeId;
        }

        return $validated;
    }
}
