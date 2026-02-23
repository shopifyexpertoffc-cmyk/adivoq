<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContactEnquiry;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display listing
     */
    public function index(Request $request)
    {
        $query = ContactEnquiry::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contacts = $query->latest()->paginate(20);

        $stats = [
            'total' => ContactEnquiry::count(),
            'new' => ContactEnquiry::where('status', 'new')->count(),
            'read' => ContactEnquiry::where('status', 'read')->count(),
            'replied' => ContactEnquiry::where('status', 'replied')->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'stats'));
    }

    /**
     * Show details
     */
    public function show(ContactEnquiry $contact)
    {
        // Mark as read if new
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Delete entry
     */
    public function destroy(ContactEnquiry $contact)
    {
        ActivityLog::log('deleted', 'Contact enquiry deleted: ' . $contact->email, null, auth('admin')->user());

        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Enquiry deleted successfully.');
    }

    /**
     * Mark as read
     */
    public function markRead(ContactEnquiry $contact)
    {
        $contact->update(['status' => 'read']);

        return back()->with('success', 'Marked as read.');
    }

    /**
     * Reply to enquiry
     */
    public function reply(Request $request, ContactEnquiry $contact)
    {
        $request->validate([
            'reply_message' => 'required|string|max:5000',
        ]);

        // TODO: Send reply email

        $contact->update(['status' => 'replied']);

        ActivityLog::log('replied', 'Replied to contact enquiry: ' . $contact->email, $contact, auth('admin')->user());

        return back()->with('success', 'Reply sent successfully.');
    }
}