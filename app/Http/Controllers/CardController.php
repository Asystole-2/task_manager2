<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Column;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Column $column)
    {
        $this->authorize('view', $column->board);

        return response()->json([
            'cards' => $column->cards()->orderBy('order')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Column $column)
    {
        $this->authorize('update', $column->board);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string'
        ]);

        $order = $column->cards()->max('order') + 1;

        $card = $column->cards()->create(array_merge($validated, [
            'order' => $order
        ]));

        return response()->json($card, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        $this->authorize('view', $card->column->board);

        return response()->json($card);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        $this->authorize('update', $card->column->board);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'nullable|string',
            'column_id' => 'sometimes|exists:columns,id'
        ]);

        $card->update($validated);

        return response()->json($card);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        $this->authorize('delete', $card->column->board);

        $card->delete();

        // Reorder remaining cards in the column
        $cards = $card->column->cards()->orderBy('order')->get();
        foreach ($cards as $index => $c) {
            $c->update(['order' => $index]);
        }

        return response()->json(null, 204);
    }

    /**
     * Handle card reordering
     */
    public function move(Card $card, Request $request)
    {
        $this->authorize('update', $card->column->board);

        $request->validate([
            'newOrder' => 'required|integer',
            'newColumnId' => 'sometimes|exists:columns,id'
        ]);

        $oldColumnId = $card->column_id;
        $newColumnId = $request->newColumnId ?? $oldColumnId;
        $oldOrder = $card->order;
        $newOrder = $request->newOrder;

        // If moving to a different column
        if ($oldColumnId != $newColumnId) {
            // Decrement order of cards in old column that were after this card
            $card->column->cards()
                ->where('order', '>', $oldOrder)
                ->decrement('order');

            // Increment order of cards in new column that are after new position
            Card::where('column_id', $newColumnId)
                ->where('order', '>=', $newOrder)
                ->increment('order');

            $card->update([
                'column_id' => $newColumnId,
                'order' => $newOrder
            ]);
        } else {
            // Same column reordering
            if ($oldOrder < $newOrder) {
                // Moving down
                $card->column->cards()
                    ->where('order', '>', $oldOrder)
                    ->where('order', '<=', $newOrder)
                    ->decrement('order');
            } else {
                // Moving up
                $card->column->cards()
                    ->where('order', '>=', $newOrder)
                    ->where('order', '<', $oldOrder)
                    ->increment('order');
            }

            $card->update(['order' => $newOrder]);
        }

        return response()->json(['message' => 'Card moved successfully']);
    }
}
