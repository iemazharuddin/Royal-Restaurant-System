<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Model;

class GalleryController extends AdminResourceController
{
    protected string $modelClass = Gallery::class;
    protected string $resource = 'gallery';
    protected string $title = 'Gallery';
    protected string $description = 'Upload and organize restaurant and food photos.';

    protected array $columns = [
        ['key' => 'image', 'label' => 'Photo', 'type' => 'image'],
        ['key' => 'title', 'label' => 'Title'],
        ['key' => 'category', 'label' => 'Category'],
        ['key' => 'is_active', 'label' => 'Visible'],
        ['key' => 'sort_order', 'label' => 'Order'],
    ];

    protected array $searchFields = [
        'title',
        'category',
        'description',
    ];

    protected array $fields = [
        ['name' => 'title', 'label' => 'Photo Title', 'type' => 'text', 'required' => true],
        ['name' => 'image', 'label' => 'Photo', 'type' => 'file'],
        ['name' => 'category', 'label' => 'Category', 'type' => 'text'],
        ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
        ['name' => 'sort_order', 'label' => 'Display Order', 'type' => 'number', 'default' => 0],
        ['name' => 'is_active', 'label' => 'Visible on Website', 'type' => 'checkbox', 'default' => true],
    ];

    protected function rules(?Model $record): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'image' => [
                $record ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
