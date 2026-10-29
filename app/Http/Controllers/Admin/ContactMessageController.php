<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->value();
        $messages = ContactMessage::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.contacts.index', compact('messages', 'status'));
    }

    public function update(Request $request, ContactMessage $contact): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:unread,read,closed']]);
        $contact->update($data);

        return back()->with('success', __('Contact message updated.'));
    }

    public function destroy(ContactMessage $contact): RedirectResponse
    {
        $contact->delete();
        return back()->with('success', __('Contact message deleted.'));
    }
}
