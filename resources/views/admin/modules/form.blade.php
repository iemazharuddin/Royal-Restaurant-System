@extends('layouts.admin')

@php
    $resource = $resource ?? 'orders';
    $isEditing = $isEditing ?? false;
    $record = $record ?? ($item ?? ($model ?? null));
    $fields = $fields ?? [];

    $formTitle = $title ?? (
        ($isEditing ? 'Edit ' : 'Create ') .
        ucwords(str_replace(['-', '_'], ' ', $resource))
    );

    // Shared compact styling
    $labelClass   = 'muted mb-1 block text-[10px] font-medium uppercase tracking-wide';
    $controlClass = 'w-full rounded-xl border px-3 py-2.5 text-[13px] outline-none focus:border-yellow-500';
    $controlStyle = 'background:var(--bg);border-color:var(--border);color:var(--text);';
@endphp

@section('title', $formTitle)
@section('page-heading', $formTitle)

@push('styles')
<style>
    /* Fallback (Firefox / Safari / older browsers) */
    select.admin-select option {
        font-size: 13px;
        padding: 8px 10px;
        background: var(--surface);
        color: var(--text);
    }

    /* Fully styled dropdown (Chrome / Edge) */
    @supports (appearance: base-select) {
        select.admin-select:not([multiple]),
        select.admin-select:not([multiple])::picker(select) {
            appearance: base-select;
        }

        select.admin-select:not([multiple])::picker(select) {
            margin-top: 4px;
            padding: 4px;
            max-height: 240px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--surface);
            box-shadow: 0 14px 34px rgba(9, 18, 14, .18);
        }

        select.admin-select:not([multiple])::picker-icon {
            color: var(--muted);
            transition: rotate .2s ease;
        }
        select.admin-select:not([multiple]):open::picker-icon { rotate: 180deg; }

        select.admin-select:not([multiple]) option {
            padding: 8px 10px;
            border-radius: 8px;
            font-size: 12.5px;
            background: transparent;
            color: var(--text);
            cursor: pointer;
        }
        select.admin-select:not([multiple]) option:hover,
        select.admin-select:not([multiple]) option:focus {
            background: rgba(214, 173, 105, .18);
        }
        select.admin-select:not([multiple]) option:checked {
            background: rgba(214, 173, 105, .28);
            font-weight: 700;
        }
        select.admin-select:not([multiple]) option::checkmark { display: none; }
    }
</style>
@endpush

@section('content')

<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">Management</p>
            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">{{ $formTitle }}</h1>
            <p class="muted mt-1 text-xs">
                {{ $isEditing ? 'Update the existing record information.' : 'Enter information to create a new record.' }}
            </p>
        </div>

        <a href="{{ route('admin.' . $resource . '.index') }}"
           class="admin-detail-action !px-3.5 !py-2 !text-xs border"
           style="border-color:var(--border);">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-green-500/10 p-3 text-xs text-green-600">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-300 bg-red-50 p-3 text-xs text-red-700">
            <strong><i class="fas fa-exclamation-circle mr-1"></i> Please fix the following errors:</strong>
            <ul class="mt-1.5 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        <div class="mb-4 flex items-center gap-2.5 border-b pb-4" style="border-color:var(--border);">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sm text-gold"
                 style="background:linear-gradient(145deg,#2c4236,#111c18);">
                <i class="fas {{ $isEditing ? 'fa-pen-to-square' : 'fa-plus' }}"></i>
            </div>
            <div>
                <h2 class="text-base font-bold leading-tight">
                    {{ $isEditing ? 'Update Information' : 'New Information' }}
                </h2>
                <p class="muted text-[11px]">Fill in the required fields below.</p>
            </div>
        </div>

        <form
            action="{{ $isEditing ? route('admin.' . $resource . '.update', $record) : route('admin.' . $resource . '.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            @if($isEditing)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                @foreach($fields as $key => $field)

                    @php
                        $config = is_array($field)
                            ? $field
                            : ['type' => is_string($field) ? $field : 'text'];

                        $name = $config['name'] ?? (is_string($key) ? $key : null);

                        $type = $config['type'] ?? 'text';

                        $label = $config['label'] ?? (
                            $name
                                ? ucwords(str_replace(['_', '-'], ' ', $name))
                                : 'Field'
                        );

                        $placeholder = $config['placeholder'] ?? 'Enter ' . strtolower($label);

                        $required = $config['required'] ?? false;

                        $options = $config['options'] ?? [];

                        $rows = $config['rows'] ?? 4;

                        // Get original value
                        $rawValue = $name
                            ? old($name, data_get($record, $name, $config['default'] ?? ''))
                            : '';

                        $fieldId = 'field_' . ($name ?? $loop->index);

                        // Format date values
                        if ($rawValue instanceof \DateTimeInterface) {

                            if ($type === 'date') {
                                $rawValue = $rawValue->format('Y-m-d');
                            } elseif ($type === 'datetime-local') {
                                $rawValue = $rawValue->format('Y-m-d\TH:i');
                            } elseif ($type === 'time') {
                                $rawValue = $rawValue->format('H:i');
                            }
                        }

                        // Convert Laravel Arrayable objects
                        if ($rawValue instanceof \Illuminate\Contracts\Support\Arrayable) {
                            $rawValue = $rawValue->toArray();
                        }

                        // Convert objects to arrays
                        if (is_object($rawValue)) {
                            $rawValue = (array) $rawValue;
                        }

                        // Safely convert arrays into JSON strings
                        if (is_array($rawValue)) {
                            $inputValue = json_encode(
                                $rawValue,
                                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                            );
                        } elseif (is_bool($rawValue)) {
                            $inputValue = $rawValue ? '1' : '0';
                        } elseif (is_scalar($rawValue)) {
                            $inputValue = (string) $rawValue;
                        } else {
                            $inputValue = '';
                        }

                        $fullWidth = in_array($type, ['textarea', 'file', 'hidden']);
                    @endphp

                    @if($name)

                        {{-- Hidden Input --}}
                        @if($type === 'hidden')

                            <input type="hidden" name="{{ $name }}" value="{{ $inputValue }}">

                        @else

                            <div class="{{ $fullWidth ? 'sm:col-span-2' : '' }}">

                                {{-- Label --}}
                                <label for="{{ $fieldId }}" class="{{ $labelClass }}">
                                    {{ $label }}

                                    @if($required)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                {{-- Textarea --}}
                                @if($type === 'textarea')

                                    <textarea
                                        id="{{ $fieldId }}"
                                        name="{{ $name }}"
                                        rows="{{ $rows }}"
                                        placeholder="{{ $placeholder }}"
                                        {{ $required ? 'required' : '' }}
                                        class="{{ $controlClass }} leading-6"
                                        style="{{ $controlStyle }}"
                                    >{{ $inputValue }}</textarea>

                                {{-- Select --}}
                                @elseif($type === 'select')

                                    <select
                                        id="{{ $fieldId }}"
                                        name="{{ $name }}{{ !empty($config['multiple']) ? '[]' : '' }}"
                                        {{ $required ? 'required' : '' }}
                                        {{ !empty($config['multiple']) ? 'multiple' : '' }}
                                        class="{{ $controlClass }} admin-select"
                                        style="{{ $controlStyle }}"
                                    >

                                        @if(empty($config['multiple']))
                                            <option value="">
                                                Select {{ strtolower($label) }}
                                            </option>
                                        @endif

                                        @foreach($options as $optionValue => $optionLabel)

                                            @php
                                                // Associative options use their array key as the submitted value.
                                                // This is important for Order dropdowns: numeric order IDs
                                                // must be submitted, not the visible order number.
                                                $actualValue = array_is_list($options)
                                                    ? $optionLabel
                                                    : $optionValue;

                                                $selected = is_array($rawValue)
                                                    ? in_array((string) $actualValue, array_map('strval', $rawValue))
                                                    : (string) $rawValue === (string) $actualValue;
                                            @endphp

                                            <option
                                                value="{{ $actualValue }}"
                                                {{ $selected ? 'selected' : '' }}
                                            >
                                                {{ $optionLabel }}
                                            </option>

                                        @endforeach

                                    </select>

                                {{-- Checkbox --}}
                                @elseif($type === 'checkbox')

                                    <input type="hidden" name="{{ $name }}" value="0">

                                    <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                                        <label class="flex cursor-pointer items-center gap-2.5">

                                            <input
                                                type="checkbox"
                                                id="{{ $fieldId }}"
                                                name="{{ $name }}"
                                                value="1"
                                                {{ (bool) $rawValue ? 'checked' : '' }}
                                                class="h-4 w-4 accent-yellow-500"
                                            >

                                            <span class="text-[13px] font-semibold">
                                                {{ $config['checkbox_label'] ?? $label }}
                                            </span>

                                        </label>
                                    </div>

                                {{-- Radio --}}
                                @elseif($type === 'radio')

                                    <div class="admin-info-item !rounded-xl !px-3 !py-2.5 flex flex-wrap gap-x-5 gap-y-2">

                                        @foreach($options as $optionValue => $optionLabel)

                                            @php
                                                $actualValue = is_int($optionValue)
                                                    ? $optionLabel
                                                    : $optionValue;
                                            @endphp

                                            <label class="flex cursor-pointer items-center gap-2 text-[13px] font-semibold">

                                                <input
                                                    type="radio"
                                                    name="{{ $name }}"
                                                    value="{{ $actualValue }}"
                                                    {{ (string) $rawValue === (string) $actualValue ? 'checked' : '' }}
                                                    {{ $required ? 'required' : '' }}
                                                    class="h-4 w-4 accent-yellow-500"
                                                >

                                                {{ $optionLabel }}

                                            </label>

                                        @endforeach

                                    </div>

                                {{-- File --}}
                                @elseif($type === 'file')

                                    <input
                                        type="file"
                                        id="{{ $fieldId }}"
                                        name="{{ $name }}"
                                        accept="{{ $config['accept'] ?? '' }}"
                                        {{ $required && !$inputValue ? 'required' : '' }}
                                        class="{{ $controlClass }}"
                                        style="{{ $controlStyle }}"
                                    >

                                    @if($inputValue)
                                        <p class="muted mt-1 text-[11px]">
                                            Existing file: {{ $inputValue }}
                                        </p>
                                    @endif

                                {{-- Normal Inputs --}}
                                @else

                                    <input
                                        type="{{ $type }}"
                                        id="{{ $fieldId }}"
                                        name="{{ $name }}"
                                        value="{{ $inputValue }}"
                                        placeholder="{{ $placeholder }}"
                                        {{ $required ? 'required' : '' }}
                                        @if(isset($config['min'])) min="{{ $config['min'] }}" @endif
                                        @if(isset($config['max'])) max="{{ $config['max'] }}" @endif
                                        @if(isset($config['step'])) step="{{ $config['step'] }}" @endif
                                        @if(isset($config['maxlength'])) maxlength="{{ $config['maxlength'] }}" @endif
                                        class="{{ $controlClass }}"
                                        style="{{ $controlStyle }}"
                                    >

                                @endif

                                {{-- Help Text --}}
                                @if(!empty($config['help']))
                                    <p class="muted mt-1 text-[11px]">
                                        {{ $config['help'] }}
                                    </p>
                                @endif

                                {{-- Field Error --}}
                                @error($name)
                                    <p class="mt-1 text-xs text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        @endif

                    @endif

                @endforeach

            </div>

            {{-- Form Buttons --}}
            <div class="mt-5 flex flex-wrap gap-2 border-t pt-4" style="border-color:var(--border);">

                <button type="submit"
                        class="admin-detail-action !px-3.5 !py-2 !text-xs"
                        style="color:#111c18;background:linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);">
                    <i class="fas fa-save"></i>
                    {{ $isEditing ? 'Save Changes' : 'Create Record' }}
                </button>

                <a href="{{ route('admin.' . $resource . '.index') }}"
                   class="admin-detail-action !px-3.5 !py-2 !text-xs border"
                   style="border-color:var(--border);">
                    Cancel
                </a>

            </div>

        </form>

    </section>

</div>

@endsection