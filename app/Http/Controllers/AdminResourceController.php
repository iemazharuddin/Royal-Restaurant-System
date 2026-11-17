<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

abstract class AdminResourceController extends Controller
{
    protected string $modelClass;
    protected string $resource;
    protected string $title;
    protected string $description;
    protected array $columns = [];
    protected array $fields = [];
    protected array $searchFields = [];
    protected array $with = [];

    public function index(Request $request)
    {
        $model = $this->modelClass;
        $query = $model::query()->with($this->with);
        $search = trim((string) $request->query('search', ''));

        if ($search !== '' && $this->searchFields !== []) {
            $query->where(function ($builder) use ($search) {
                foreach ($this->searchFields as $index => $field) {
                    $method = $index === 0 ? 'where' : 'orWhere';
                    $builder->{$method}($field, 'like', '%' . $search . '%');
                }
            });
        }

        $records = $query->latest()->paginate(15)->withQueryString();

        return view('admin.modules.index', [
            'title' => $this->title,
            'description' => $this->description,
            'resource' => $this->resource,
            'columns' => $this->columns,
            'records' => $records,
        ]);
    }

    public function create()
    {
        return $this->form();
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(null));
        $validated = $this->prepareData($validated, $request, null);
        $model = $this->modelClass;
        $model::create($validated);

        return redirect()
            ->route('admin.' . $this->resource . '.index')
            ->with('success', $this->title . ' created successfully.');
    }

    public function edit(string $id)
    {
        $model = $this->modelClass;

        return $this->form($model::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $model = $this->modelClass;
        $record = $model::findOrFail($id);
        $validated = $request->validate($this->rules($record));
        $record->update($this->prepareData($validated, $request, $record));

        return redirect()
            ->route('admin.' . $this->resource . '.index')
            ->with('success', $this->title . ' updated successfully.');
    }

    public function destroy(string $id)
    {
        $model = $this->modelClass;
        $record = $model::findOrFail($id);

        foreach ($this->fields as $field) {
            if (($field['type'] ?? null) === 'file' && $record->{$field['name']}) {
                Storage::disk('public')->delete($record->{$field['name']});
            }
        }

        $record->delete();

        return redirect()
            ->route('admin.' . $this->resource . '.index')
            ->with('success', $this->title . ' deleted successfully.');
    }

    protected function rules(?Model $record): array
    {
        return [];
    }

    protected function prepareData(array $validated, Request $request, ?Model $record): array
    {
        foreach ($this->fields as $field) {
            $name = $field['name'];
            $type = $field['type'] ?? 'text';

            if ($type === 'checkbox') {
                $validated[$name] = $request->boolean($name);
            } elseif ($type === 'file') {
                if ($request->hasFile($name)) {
                    if ($record && $record->{$name}) {
                        Storage::disk('public')->delete($record->{$name});
                    }

                    $validated[$name] = $request->file($name)->store($this->resource, 'public');
                } else {
                    unset($validated[$name]);
                }
            } elseif ($type === 'list' && isset($validated[$name])) {
                $validated[$name] = array_values(array_filter(
                    array_map('trim', preg_split('/\r\n|\r|\n/', $validated[$name])),
                    fn (string $item) => $item !== ''
                ));
            }
        }

        return $validated;
    }

    private function form(?Model $record = null)
    {
        $fields = $this->fields;

        foreach ($fields as &$field) {
            if (isset($field['options']) && is_callable($field['options'])) {
                $field['options'] = ($field['options'])();
            }
        }

        return view('admin.modules.form', [
            'title' => $this->title,
            'description' => $this->description,
            'resource' => $this->resource,
            'fields' => $fields,
            'record' => $record,
        ]);
    }
}
