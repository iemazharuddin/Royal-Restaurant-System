<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display restaurant settings.
     */
    public function index()
    {
        $settings = Setting::orderBy('key')->get();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Redirect to settings page.
     */
    public function create()
    {
        return redirect()->route('admin.settings.index');
    }

    /**
     * Save or update restaurant settings.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'opening_hours' => ['nullable', 'string', 'max:1000'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        // Store new logo if uploaded.
        $logo = null;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->store('settings', 'public');
        }

        unset($validated['logo']);

        // Save all text settings.
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Replace old logo with the new one.
        if ($logo) {
            $oldLogo = Setting::where('key', 'logo')->value('value');

            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            Setting::updateOrCreate(
                ['key' => 'logo'],
                ['value' => $logo]
            );
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Restaurant settings saved successfully!');
    }

    /**
     * Redirect to settings page.
     */
    public function show(Setting $setting)
    {
        return redirect()->route('admin.settings.index');
    }

    /**
     * Redirect to settings page.
     */
    public function edit(Setting $setting)
    {
        return redirect()->route('admin.settings.index');
    }

    /**
     * Update an individual setting.
     */
    public function update(Request $request, Setting $setting)
    {
        $validated = $request->validate([
            'value' => ['nullable', 'string', 'max:5000'],
        ]);

        $setting->update($validated);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Setting updated successfully!');
    }

    /**
     * Delete a setting.
     */
    public function destroy(Setting $setting)
    {
        if ($setting->key === 'logo' && $setting->value) {
            Storage::disk('public')->delete($setting->value);
        }

        $setting->delete();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Setting deleted successfully!');
    }
}
