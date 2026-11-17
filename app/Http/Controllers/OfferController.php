<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class OfferController extends AdminResourceController
{
    protected string $modelClass = Offer::class;
    protected string $resource = 'offers';
    protected string $title = 'Offers & Discounts';
    protected string $description = 'Create and schedule promotional discounts.';

    protected array $columns = [
        ['key' => 'title', 'label' => 'Offer'],
        ['key' => 'code', 'label' => 'Code'],
        ['key' => 'discount_type', 'label' => 'Discount Type'],
        ['key' => 'discount_value', 'label' => 'Value'],
        ['key' => 'ends_at', 'label' => 'Ends'],
        ['key' => 'is_active', 'label' => 'Active'],
    ];

    protected array $searchFields = [
        'title',
        'code',
    ];

    protected array $fields = [
        ['name' => 'title', 'label' => 'Offer Title', 'type' => 'text', 'required' => true],
        ['name' => 'code', 'label' => 'Promo Code', 'type' => 'text'],
        ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
        [
            'name' => 'discount_type',
            'label' => 'Discount Type',
            'type' => 'select',
            'required' => true,
            'options' => [
                'percentage' => 'Percentage',
                'fixed' => 'Fixed Amount',
            ],
        ],
        ['name' => 'discount_value', 'label' => 'Discount Value', 'type' => 'number', 'required' => true, 'step' => '0.01'],
        ['name' => 'minimum_order', 'label' => 'Minimum Order (Rs.)', 'type' => 'number', 'step' => '0.01'],
        ['name' => 'starts_at', 'label' => 'Starts At', 'type' => 'datetime-local'],
        ['name' => 'ends_at', 'label' => 'Ends At', 'type' => 'datetime-local'],
        ['name' => 'is_active', 'label' => 'Active', 'type' => 'checkbox', 'default' => true],
    ];

    protected function rules(?Model $record): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('offers', 'code')->ignore($record?->id),
            ],
            'description' => ['nullable', 'string', 'max:5000'],
            'discount_type' => ['required', Rule::in(['percentage', 'fixed'])],
            'discount_value' => ['required', 'numeric', 'gt:0'],
            'minimum_order' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
