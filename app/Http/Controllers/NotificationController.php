<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationController extends AdminResourceController
{
    protected string $modelClass = Notification::class;
    protected string $resource = 'notifications';
    protected string $title = 'Notifications';
    protected string $description = 'Manage admin and customer alert records.';

    protected array $columns = [
        ['key' => 'title', 'label' => 'Notification'],
        ['key' => 'audience', 'label' => 'Audience'],
        ['key' => 'recipient_email', 'label' => 'Recipient'],
        ['key' => 'type', 'label' => 'Type'],
        ['key' => 'sent_at', 'label' => 'Created'],
    ];

    protected array $searchFields = [
        'title',
        'message',
        'recipient_email',
    ];

    protected array $fields = [
        ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => true],
        ['name' => 'message', 'label' => 'Message', 'type' => 'textarea', 'required' => true],
        [
            'name' => 'audience',
            'label' => 'Audience',
            'type' => 'select',
            'required' => true,
            'options' => [
                'admin' => 'Admin',
                'customers' => 'Customers',
                'all' => 'Everyone',
            ],
        ],
        ['name' => 'recipient_email', 'label' => 'Specific Customer Email (Optional)', 'type' => 'email'],
        [
            'name' => 'type',
            'label' => 'Alert Type',
            'type' => 'select',
            'required' => true,
            'options' => [
                'info' => 'Information',
                'success' => 'Success',
                'warning' => 'Warning',
                'urgent' => 'Urgent',
            ],
        ],
        ['name' => 'link', 'label' => 'Related Link (Optional)', 'type' => 'url'],
        ['name' => 'is_read', 'label' => 'Marked as Read', 'type' => 'checkbox'],
    ];

    protected function rules(?Model $record): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'audience' => ['required', Rule::in(['admin', 'customers', 'all'])],
            'recipient_email' => ['nullable', 'email', 'max:255'],
            'type' => ['required', Rule::in(['info', 'success', 'warning', 'urgent'])],
            'link' => ['nullable', 'url', 'max:2048'],
            'is_read' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareData(
        array $validated,
        Request $request,
        ?Model $record
    ): array {
        $validated = parent::prepareData($validated, $request, $record);

        $validated['sent_at'] = $record?->sent_at ?? now();

        return $validated;
    }
}
