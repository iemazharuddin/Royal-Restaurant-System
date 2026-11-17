
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

    $labelClass = 'muted mb-1 block text-[10px] font-medium uppercase tracking-wide';
    $controlClass = 'w-full rounded-xl border px-3 py-2.5 text-[13px] outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/10 transition';
    $controlStyle = 'background:var(--bg);border-color:var(--border);color:var(--text);';
@endphp

@section('title', $formTitle)
@section('page-heading', $formTitle)

@push('styles')
<style>
    select.admin-select option {
        font-size: 13px;
        padding: 8px 10px;
        background: var(--surface);
        color: var(--text);
    }

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
            box-shadow: 0 14px 34px rgba(9,18,14,.18);
        }

        select.admin-select:not([multiple])::picker-icon {
            color: var(--muted);
            transition: rotate .2s ease;
        }

        select.admin-select:not([multiple]):open::picker-icon {
            rotate: 180deg;
        }

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
            background: rgba(214,173,105,.18);
        }

        select.admin-select:not([multiple]) option:checked {
            background: rgba(214,173,105,.28);
            font-weight: 700;
        }

        select.admin-select:not([multiple]) option::checkmark {
            display: none;
        }
    }

    /* Modern Gold & White Buttons */
    .gold-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 16px;
        border-radius: 11px;
        background: linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);
        color: #211b10;
        font-size: 12px;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(161,123,65,.20), inset 0 1px 0 rgba(255,255,255,.45);
        transition: all .2s ease;
    }

    .gold-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 16px rgba(161,123,65,.28);
        filter: brightness(1.03);
    }

    .gold-btn:active {
        transform: translateY(0);
    }

    .white-gold-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 16px;
        border: 1px solid #eadfc5;
        border-radius: 11px;
        background: #fff;
        color: #94702c;
        font-size: 12px;
        font-weight: 800;
        box-shadow: 0 3px 9px rgba(90,70,30,.05);
        transition: all .2s ease;
    }

    .white-gold-btn:hover {
        background: #fff9e9;
        border-color: #d6b45c;
        transform: translateY(-1px);
    }

    .gold-btn:disabled {
        opacity: .65;
        cursor: not-allowed;
        transform: none;
    }

    .form-control-focus:focus {
        border-color: #d6ad69 !important;
        box-shadow: 0 0 0 3px rgba(214,173,105,.12);
    }
</style>
@endpush

@section('content')

<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-[#b18a3b]">
                Management
            </p>

            <h1 class="mt-1 text-xl font-extrabold text-[#302a20] sm:text-2xl"
                style="font-family:'Playfair Display',serif;">
                {{ $formTitle }}
            </h1>

            <p class="muted mt-1 text-xs">
                {{ $isEditing ? 'Update the existing record information.' : 'Enter information to create a new record.' }}
            </p>
        </div>

        <a href="{{ route('admin.' . $resource . '.index') }}"
           class="white-gold-btn">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-3 text-xs text-green-700">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-3 text-xs text-red-700">
            <strong>
                <i class="fas fa-exclamation-circle mr-1"></i>
                Please fix the following errors:
            </strong>

            <ul class="mt-1.5 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <section class="surface admin-detail-card !rounded-2xl border border-[#eee4ce] p-4 shadow-[0_5px_20px_rgba(90,70,30,.05)] sm:p-5">

        {{-- Card Heading --}}
        <div class="mb-4 flex items-center gap-2.5 border-b pb-4"
             style="border-color:var(--border);">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm text-[#785819] shadow-md"
                 style="background:linear-gradient(135deg,#f8e9bd,#d6b45c);">
                <i class="fas {{ $isEditing ? 'fa-pen-to-square' : 'fa-plus' }}"></i>
            </div>

            <div>
                <h2 class="text-base font-bold leading-tight text-[#302a20]">
                    {{ $isEditing ? 'Update Information' : 'New Information' }}
                </h2>
                <p class="muted text-[11px]">
                    Fill in the required fields below.
                </p>
            </div>
        </div>

        {{-- Form --}}
        <form
            action="{{ $isEditing ? route('admin.' . $resource . '.update', $record) : route('admin.' . $resource . '.store') }}"
            method="POST"
            enctype="multipart/form-data"
            id="resourceForm"
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

                        $rawValue = $name
                            ? old($name, data_get($record, $name, $config['default'] ?? ''))
                            : '';

                        $fieldId = 'field_' . ($name ?? $loop->index);

                        if ($rawValue instanceof \DateTimeInterface) {
                            if ($type === 'date') {
                                $rawValue = $rawValue->format('Y-m-d');
                            } elseif ($type === 'datetime-local') {
                                $rawValue = $rawValue->format('Y-m-d\TH:i');
                            } elseif ($type === 'time') {
                                $rawValue = $rawValue->format('H:i');
                            }
                        }

                        if ($rawValue instanceof \Illuminate\Contracts\Support\Arrayable) {
                            $rawValue = $rawValue->toArray();
                        }

                        if (is_object($rawValue)) {
                            $rawValue = (array) $rawValue;
                        }

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

                        {{-- Hidden --}}
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
                                        class="{{ $controlClass }} form-control-focus leading-6"
                                        style="{{ $controlStyle }}"
                                    >{{ $inputValue }}</textarea>

                                {{-- Select --}}
                                @elseif($type === 'select')

                                    <select
                                        id="{{ $fieldId }}"
                                        name="{{ $name }}{{ !empty($config['multiple']) ? '[]' : '' }}"
                                        {{ $required ? 'required' : '' }}
                                        {{ !empty($config['multiple']) ? 'multiple' : '' }}
                                        class="{{ $controlClass }} admin-select form-control-focus"
                                        style="{{ $controlStyle }}"
                                    >

                                        @if(empty($config['multiple']))
                                            <option value="">
                                                Select {{ strtolower($label) }}
                                            </option>
                                        @endif

                                        @foreach($options as $optionValue => $optionLabel)

                                            @php
                                                $actualValue = array_is_list($options)
                                                    ? $optionLabel
                                                    : $optionValue;

                                                $selected = is_array($rawValue)
                                                    ? in_array((string) $actualValue, array_map('strval', $rawValue))
                                                    : (string) $rawValue === (string) $actualValue;
                                            @endphp

                                            <option value="{{ $actualValue }}"
                                                {{ $selected ? 'selected' : '' }}>
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
                                                class="h-4 w-4 accent-yellow-600"
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
                                                    class="h-4 w-4 accent-yellow-600"
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
                                        class="{{ $controlClass }} form-control-focus"
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

            {{-- Modern Gold & White Action Buttons --}}
            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t pt-4"
                 style="border-color:var(--border);">

                <a href="{{ route('admin.' . $resource . '.index') }}"
                   class="white-gold-btn">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>

                <button type="submit" id="submitButton" class="gold-btn">
                    <i class="fas {{ $isEditing ? 'fa-save' : 'fa-plus-circle' }}"></i>
                    {{ $isEditing ? 'Save Changes' : 'Create Record' }}
                </button>

            </div>

        </form>

    </section>

</div>

@endsection

@push('scripts')
<script>
    document.getElementById('resourceForm')?.addEventListener('submit', function () {
        const button = document.getElementById('submitButton');

        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Please wait...';
        }
    });
</script>
@endpush
