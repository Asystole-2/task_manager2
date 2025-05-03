<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'all_day' => 'boolean',
            'color' => 'nullable|string|max:50'
        ]);

        $event = CalendarEvent::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?? $request->start_date,
            'all_day' => $request->all_day ?? false,
            'color' => $request->color
        ]);

        return response()->json($event, 201);
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, CalendarEvent $event)
    {
        $this->authorize('update', $event);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'all_day' => 'sometimes|boolean',
            'color' => 'nullable|string|max:50'
        ]);

        $event->update($request->all());

        return response()->json($event);
    }

    /**
     * Remove the specified event.
     */
    public function destroy(CalendarEvent $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return response()->json(null, 204);
    }
}
