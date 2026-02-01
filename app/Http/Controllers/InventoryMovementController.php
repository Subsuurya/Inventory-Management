<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryMovementRequest;
use App\Models\InventoryMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryMovementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = InventoryMovement::query()
            ->with(['product', 'inventoryBatch']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('inventory_batch_id')) {
            $query->where('inventory_batch_id', $request->inventory_batch_id);
        }
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        $movements = $query->latest()->paginate(15);

        return response()->json($movements);
    }

    public function store(StoreInventoryMovementRequest $request): JsonResponse
    {
        $batch = \App\Models\InventoryBatch::findOrFail($request->inventory_batch_id);

        $quantity = (float) $request->quantity;
        if ($request->movement_type === 'OUT' && $quantity > 0) {
            $quantity = -$quantity;
        }
        if ($request->movement_type === 'IN' && $quantity < 0) {
            $quantity = abs($quantity);
        }

        $movement = InventoryMovement::create([
            'product_id' => $request->product_id,
            'inventory_batch_id' => $request->inventory_batch_id,
            'movement_type' => $request->movement_type,
            'quantity' => $quantity,
            'reference_type' => $request->reference_type,
            'reference_id' => $request->reference_id,
        ]);

        $batch->increment('quantity_remaining', $quantity);

        $movement->load(['product', 'inventoryBatch']);

        return response()->json($movement, 201);
    }

    public function show(InventoryMovement $inventory_movement): JsonResponse
    {
        $inventory_movement->load(['product', 'inventoryBatch']);

        return response()->json($inventory_movement);
    }
}
