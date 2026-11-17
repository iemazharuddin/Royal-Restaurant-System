<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\RestaurantTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrderController extends AdminResourceController
{
    protected string $modelClass = Order::class;
    protected string $resource = 'orders';
    protected string $title = 'Orders';
    protected string $description = 'Track online, delivery, takeaway and dine-in orders.';

    protected array $with = [
        'customer',
        'restaurantTable',
    ];

    protected array $columns = [
        ['key' => 'order_number', 'label' => 'Order'],
        ['key' => 'customer.name', 'label' => 'Customer'],
        ['key' => 'order_type', 'label' => 'Type'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'total', 'label' => 'Total'],
        ['key' => 'created_at', 'label' => 'Date'],
    ];

    protected array $searchFields = [
        'order_number',
        'status',
        'order_type',
    ];

    protected array $fields = [
        [
            'name' => 'customer_id',
            'label' => 'Customer',
            'type' => 'select',
            'options' => [self::class, 'customerOptions'],
        ],
        [
            'name' => 'restaurant_table_id',
            'label' => 'Restaurant Table',
            'type' => 'select',
            'options' => [self::class, 'tableOptions'],
        ],
        [
            'name' => 'order_type',
            'label' => 'Order Type',
            'type' => 'select',
            'required' => true,
            'options' => [
                'dine_in' => 'Dine In',
                'takeaway' => 'Takeaway',
                'delivery' => 'Delivery',
            ],
        ],
        [
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select',
            'required' => true,
            'options' => [
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'preparing' => 'Preparing',
                'ready' => 'Ready',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ],
            'default' => 'pending',
        ],
        ['name' => 'items', 'label' => 'Items (One Per Line)', 'type' => 'list', 'required' => true],
        ['name' => 'subtotal', 'label' => 'Subtotal (Rs.)', 'type' => 'number', 'required' => true, 'step' => '0.01'],
        ['name' => 'tax', 'label' => 'Tax (Rs.)', 'type' => 'number', 'step' => '0.01', 'default' => 0],
        ['name' => 'discount', 'label' => 'Discount (Rs.)', 'type' => 'number', 'step' => '0.01', 'default' => 0],
        ['name' => 'notes', 'label' => 'Order Notes', 'type' => 'textarea'],
    ];

    public static function customerOptions(): array
    {
        return Customer::orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    public static function tableOptions(): array
    {
        return RestaurantTable::orderBy('table_number')
            ->pluck('table_number', 'id')
            ->all();
    }

    protected function rules(?Model $record): array
    {
        return [
            'customer_id' => ['nullable', 'exists:customers,id'],
            'restaurant_table_id' => ['nullable', 'exists:restaurant_tables,id'],
            'order_type' => [
                'required',
                Rule::in(['dine_in', 'takeaway', 'delivery']),
            ],
            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'confirmed',
                    'preparing',
                    'ready',
                    'completed',
                    'cancelled',
                ]),
            ],
            'items' => ['required', 'string', 'max:5000'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    protected function prepareData(
        array $validated,
        Request $request,
        ?Model $record
    ): array {
        $validated = parent::prepareData($validated, $request, $record);

        $validated['tax'] = $validated['tax'] ?? 0;
        $validated['discount'] = $validated['discount'] ?? 0;

        $validated['total'] = max(
            0,
            $validated['subtotal'] + $validated['tax'] - $validated['discount']
        );

        if ($record === null) {
            $validated['order_number'] = $this->generateOrderNumber();
        }

        return $validated;
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'RR-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
