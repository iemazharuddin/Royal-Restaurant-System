<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\RestaurantTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
        return Customer::orderBy('name')->pluck('name', 'id')->all();
    }

    public static function tableOptions(): array
    {
        return RestaurantTable::orderBy('table_number')->pluck('table_number', 'id')->all();
    }

    protected function rules(?Model $record): array
    {
        return [
            'customer_id' => ['nullable', 'exists:customers,id'],
            'restaurant_table_id' => ['nullable', 'exists:restaurant_tables,id'],
            'order_type' => ['required', Rule::in(['dine_in', 'takeaway', 'delivery'])],
            'status' => ['required', Rule::in(['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'])],
            'items' => ['required', 'string', 'max:5000'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    protected function prepareData(array $validated, Request $request, ?Model $record): array
    {
        $validated = parent::prepareData($validated, $request, $record);
        $validated['tax'] = $validated['tax'] ?? 0;
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['total'] = max(0, $validated['subtotal'] + $validated['tax'] - $validated['discount']);

        if ($record === null) {
            $validated['order_number'] = $this->generateOrderNumber();
        }

        return $validated;
    }

    /** Display complete order information in the admin panel. */
    public function show(Order $order)
    {
        $order->load(['customer', 'restaurantTable', 'payments']);

        return view('admin.orders.show', compact('order'));
    }

    /** Update an order's workflow status. */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled']),
            ],
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()
            ->back()
            ->with('success', 'Order ' . $order->order_number . ' status updated to ' . ucfirst($validated['status']) . '.');
    }

    /** Display a printable invoice for an order. */
    public function printInvoice(Order $order)
    {
        $order->load(['customer', 'restaurantTable', 'payments']);

        return view('admin.orders.invoice', compact('order'));
    }

    /** Public website checkout. */
    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'string', 'max:10000'],
        ]);

        $items = json_decode($validated['items'], true);

        if (!is_array($items) || empty($items)) {
            throw ValidationException::withMessages(['items' => 'Please select at least one menu item.']);
        }

        if (count($items) > 50) {
            throw ValidationException::withMessages(['items' => 'You can order a maximum of 50 different items.']);
        }

        $preparedItems = [];
        $subtotal = 0;

        foreach ($items as $item) {
            if (!is_array($item) || empty($item['menu_item_id']) || !isset($item['quantity'])) {
                throw ValidationException::withMessages(['items' => 'Invalid menu item information.']);
            }

            $quantity = filter_var($item['quantity'], FILTER_VALIDATE_INT);
            if (!$quantity || $quantity < 1 || $quantity > 100) {
                throw ValidationException::withMessages(['items' => 'Item quantity must be between 1 and 100.']);
            }

            $menuItem = MenuItem::whereKey($item['menu_item_id'])
                ->where('is_available', true)
                ->first();

            if (!$menuItem) {
                throw ValidationException::withMessages(['items' => 'A selected menu item is unavailable. Please refresh the menu.']);
            }

            // Always use the database price, never a browser-submitted price.
            $unitPrice = (float) $menuItem->price;
            $lineTotal = $unitPrice * $quantity;
            $subtotal += $lineTotal;

            $preparedItems[] = [
                'menu_item_id' => $menuItem->id,
                'name' => $menuItem->name,
                'size' => isset($item['size']) ? Str::limit(strip_tags((string) $item['size']), 50, '') : 'Regular',
                'quantity' => $quantity,
                'unit_price' => number_format($unitPrice, 2, '.', ''),
                'line_total' => number_format($lineTotal, 2, '.', ''),
            ];
        }

        $order = DB::transaction(function () use ($validated, $preparedItems, $subtotal) {
            $customer = Customer::firstOrNew(['phone' => $validated['phone']]);
            $customer->name = $validated['customer_name'];
            $customer->address = $validated['address'];
            $customer->save();

            return Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_id' => $customer->id,
                'restaurant_table_id' => null,
                'order_type' => 'delivery',
                'items' => $preparedItems,
                'subtotal' => $subtotal,
                'discount' => 0,
                'tax' => 0,
                'total' => $subtotal,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('home')
            ->with('success', 'Your order has been placed successfully. Order number: ' . $order->order_number);
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'RR-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
