<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function index()
    {
        $events = CalendarEvent::where('user_id', auth()->id())
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'start' => $event->start,
                    'end' => $event->end,
                    'all_day' => (bool)$event->all_day,
                    'color' => $event->color,
                    'user_id' => $event->user_id,
                    'created_at' => $event->created_at,
                    'updated_at' => $event->updated_at,
                ];
            });

        return Inertia::render('Calendar/Index', [
            'events' => $events,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'all_day' => 'boolean',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
        ]);

        $event = CalendarEvent::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start' => $validated['start'],
            'end' => $validated['end'] ?? null,
            'all_day' => $validated['all_day'] ?? false,
            'color' => $validated['color'] ?? '#e63946',
        ]);

        return redirect()->back()->with('success', 'Event created successfully!');
    }

    public function update(Request $request, CalendarEvent $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'start' => 'sometimes|required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'all_day' => 'sometimes|boolean',
            'color' => 'sometimes|nullable|string|max:7',
            'description' => 'sometimes|nullable|string',
        ]);

        $event->update($validated);

        return response()->json(['message' => 'Event updated successfully']);
    }

    public function destroy(CalendarEvent $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return redirect()->back()->with('success', 'Event deleted successfully!');
    }
}
