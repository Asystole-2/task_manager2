<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function index()
    {
        $events = CalendarEvent::where('user_id', auth()->id())->get();
        return Inertia::render('Dashboard', [
            'events' => $events,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date',
            'all_day' => 'boolean',
            'color' => 'nullable|string',
        ]);

        CalendarEvent::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'all_day' => $request->all_day ?? false,
            'color' => $request->color ?? '#e63946', // Default red
        ]);

        return redirect()->back()->with('success', 'Event created!');
    }

    public function update(Request $request, CalendarEvent $event)
    {
        $this->authorize('update', $event);

        $request->validate([
            'start' => 'required|date',
            'end' => 'nullable|date',
            'all_day' => 'boolean',
        ]);

        $event->update([
            'start' => $request->start,
            'end' => $request->end,
            'all_day' => $request->all_day,
        ]);

        return response()->json(['message' => 'Event updated']);
    }

    public function destroy(CalendarEvent $event)
    {
        $this->authorize('delete', $event);
        $event->delete();
        return redirect()->back()->with('success', 'Event deleted!');
    }
}
