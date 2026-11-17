<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PaymentController extends AdminResourceController
{
    protected string $modelClass = Payment::class;
    protected string $resource = 'payments';
    protected string $title = 'Payments';
    protected string $description = 'Review transaction records and payment status.';
    protected array $with = [
        'order',
    ];

    protected array $columns = [
        ['key' => 'payment_number', 'label' => 'Payment'],
        ['key' => 'order.order_number', 'label' => 'Order'],
        ['key' => 'amount', 'label' => 'Amount'],
        ['key' => 'payment_method', 'label' => 'Method'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'paid_at', 'label' => 'Paid At'],
    ];

    protected array $searchFields = [
        'payment_number',
        'payment_method',
        'status',
        'transaction_id',
    ];

    protected array $fields = [
        [
            'name' => 'order_id',
            'label' => 'Order',
            'type' => 'select',
            'required' => true,
            'options' => [self::class, 'orderOptions'],
        ],

        [
            'name' => 'amount',
            'label' => 'Amount (Rs.)',
            'type' => 'number',
            'required' => true,
            'step' => '0.01',
            'min' => '0',
        ],

        [
            'name' => 'payment_method',
            'label' => 'Payment Method',
            'type' => 'select',
            'required' => true,
            'options' => [
                'cash' => 'Cash',
                'card' => 'Card',
                'bank_transfer' => 'Bank Transfer',
                'online' => 'Online',
            ],
        ],

        [
            'name' => 'transaction_id',
            'label' => 'Transaction Reference',
            'type' => 'text',
        ],

        [
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select',
            'required' => true,
            'options' => [
                'pending' => 'Pending',
                'completed' => 'Completed',
                'failed' => 'Failed',
                'refunded' => 'Refunded',
            ],
        ],

        [
            'name' => 'paid_at',
            'label' => 'Paid At',
            'type' => 'datetime-local',
        ],
    ];

    /**
     * Order dropdown options.
     * Display: order_number
     * Value: actual database ID
     */
    public static function orderOptions(): array
    {
        return Order::query()
            ->orderByDesc('id')
            ->get(['id', 'order_number'])
            ->pluck('order_number', 'id')
            ->toArray();
    }

    /**
     * Payment validation rules.
     */
    protected function rules(?Model $record): array
    {
        return [
            'order_id' => [
                'required',
                'integer',
                Rule::exists('orders', 'id'),
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'payment_method' => [
                'required',
                Rule::in([
                    'cash',
                    'card',
                    'bank_transfer',
                    'online',
                ]),
            ],

            'transaction_id' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'completed',
                    'failed',
                    'refunded',
                ]),
            ],

            'paid_at' => [
                'nullable',
                'date',
            ],
        ];
    }

    /**
     * Prepare payment data before saving.
     */
    protected function prepareData(
        array $validated,
        Request $request,
        ?Model $record
    ): array {

        $validated = parent::prepareData(
            $validated,
            $request,
            $record
        );

        if ($record === null) {
            $validated['payment_number'] = $this->generatePaymentNumber();
        }

        return $validated;
    }

    /**
     * Generate unique payment number.
     */
    private function generatePaymentNumber(): string
    {
        do {
            $number = 'PAY-' .
                now()->format('Ymd') .
                '-' .
                Str::upper(Str::random(5));

        } while (
            Payment::where('payment_number', $number)->exists()
        );

        return $number;
    }
}
