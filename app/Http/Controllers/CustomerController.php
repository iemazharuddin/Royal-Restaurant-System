<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class CustomerController extends AdminResourceController
{
    protected string $modelClass = Customer::class;

    protected string $resource = 'customers';

    protected string $title = 'Customers';

    protected string $description = 'Customer records and contact information.';

    protected array $columns = [
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'phone', 'label' => 'Phone'],
        ['key' => 'created_at', 'label' => 'Added'],
    ];

    protected array $searchFields = [
        'name',
        'email',
        'phone',
    ];

    protected array $fields = [
        ['name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'required' => true],
        ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
        ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel'],
        ['name' => 'address', 'label' => 'Address', 'type' => 'textarea'],
        ['name' => 'notes', 'label' => 'Notes', 'type' => 'textarea'],
    ];

    protected function rules(?Model $record): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($record?->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:5000'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
