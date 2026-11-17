<?php

namespace App\Http\Controllers;

use App\Models\RestaurantTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class RestaurantTableController extends AdminResourceController
{
    protected string $modelClass = RestaurantTable::class;
    protected string $resource = 'tables';
    protected string $title = 'Tables Management';
    protected string $description = 'Manage seating, capacity and table availability.';

    protected array $columns = [
        ['key' => 'table_number', 'label' => 'Table'],
        ['key' => 'capacity', 'label' => 'Seats'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'description', 'label' => 'Description'],
    ];

    protected array $searchFields = [
        'table_number',
        'status',
    ];

    protected array $fields = [
        [
            'name' => 'table_number',
            'label' => 'Table name/number',
            'type' => 'text',
            'required' => true,
        ],
        [
            'name' => 'capacity',
            'label' => 'Seats',
            'type' => 'number',
            'required' => true,
            'default' => 2,
        ],
        [
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select',
            'required' => true,
            'options' => [
                'available' => 'Available',
                'occupied' => 'Occupied',
                'reserved' => 'Reserved',
                'maintenance' => 'Maintenance',
            ],
        ],
        [
            'name' => 'description',
            'label' => 'Description',
            'type' => 'textarea',
        ],
    ];

    protected function rules(?Model $record): array
    {
        return [
            'table_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('restaurant_tables', 'table_number')
                    ->ignore($record?->id),
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],

            'status' => [
                'required',
                Rule::in([
                    'available',
                    'occupied',
                    'reserved',
                    'maintenance',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }
}
