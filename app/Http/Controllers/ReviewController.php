<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Review;
use Illuminate\Database\Eloquent\Model;

class ReviewController extends AdminResourceController
{
    protected string $modelClass = Review::class;
    protected string $resource = 'reviews';
    protected string $title = 'Reviews & Ratings';
    protected string $description = 'Moderate customer feedback and ratings.';

    protected array $with = ['customer'];

    protected array $columns = [
        ['key' => 'customer_name', 'label' => 'Customer'],
        ['key' => 'rating', 'label' => 'Rating'],
        ['key' => 'comment', 'label' => 'Feedback'],
        ['key' => 'is_approved', 'label' => 'Approved'],
        ['key' => 'created_at', 'label' => 'Received'],
    ];

    protected array $searchFields = [
        'customer_name',
        'comment',
    ];

    protected array $fields = [
        [
            'name' => 'customer_id',
            'label' => 'Customer record',
            'type' => 'select',
            'options' => [self::class, 'customerOptions'],
        ],
        [
            'name' => 'customer_name',
            'label' => 'Customer name',
            'type' => 'text',
            'required' => true,
        ],
        [
            'name' => 'rating',
            'label' => 'Rating (1–5)',
            'type' => 'number',
            'required' => true,
            'default' => 5,
        ],
        [
            'name' => 'comment',
            'label' => 'Feedback',
            'type' => 'textarea',
        ],
        [
            'name' => 'is_approved',
            'label' => 'Approved for display',
            'type' => 'checkbox',
        ],
    ];

    public static function customerOptions(): array
    {
        return Customer::orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    protected function rules(?Model $record): array
    {
        return [
            'customer_id' => [
                'nullable',
                'exists:customers,id',
            ],

            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'comment' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'is_approved' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
