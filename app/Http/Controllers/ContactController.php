<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Display all contact messages
    public function index()
    {
        $messages = Contact::latest()->paginate(10);

        return view('admin.contacts.index', compact('messages'));
    }

    // Show create form
    public function create()
    {
        return view('admin.contacts.create');
    }

    // Store new contact message
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create($validated);

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Contact message created successfully.');
    }

    // Show one contact message
    public function show($id)
    {
        $message = Contact::findOrFail($id);

        return view('admin.contacts.show', compact('message'));
    }

    // Show edit form
    public function edit($id)
    {
        $message = Contact::findOrFail($id);

        return view('admin.contacts.edit', compact('message'));
    }

    // Update contact message
    public function update(Request $request, $id)
    {
        $message = Contact::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $message->update($validated);

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Contact message updated successfully.');
    }

    // Delete contact message
    public function destroy($id)
    {
        $message = Contact::findOrFail($id);

        $message->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Contact message deleted successfully.');
    }
}
