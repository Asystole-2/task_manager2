<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Correct import
use Inertia\Inertia;

class CalendarController extends Controller
{
    use AuthorizesRequests; // Use the trait for authorization

    public function index()
    {
        $events = CalendarEvent::where('user_id', auth()->id())
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description ?? '', // Ensure description always exists
                    'start' => $event->start,
                    'end' => $event->end,
                    'all_day' => (bool)$event->all_day,
                    'color' => $event->color,
                ];
            });

        return Inertia::render('Calendar/Index', [
            'events' => $events
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'all_day' => 'required|boolean',
            'color' => 'nullable|string|max:7'
        ]);

        // Convert checkbox value to proper boolean
        $allDay = filter_var($validated['all_day'], FILTER_VALIDATE_BOOLEAN);

        CalendarEvent::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start' => $validated['start'],
            'end' => $validated['end'],
            'all_day' => $allDay,
            'color' => $validated['color'] ?? '#e63946',
        ]);

        return redirect()->route('calendar.index')
            ->with('success', 'Event created successfully!');
    }

    public function destroy(CalendarEvent $event)
    {
        $this->authorize('delete-calendar-event', $event); // Ensure correct gate name
        $event->delete();

        return redirect()->back()
            ->with('success', 'Event deleted successfully!');
    }
}
