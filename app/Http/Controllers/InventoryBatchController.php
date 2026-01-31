<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryBatchRequest;
use App\Http\Requests\UpdateInventoryBatchRequest;
use App\Models\InventoryBatch;
use Illuminate\Http\JsonResponse;

class InventoryBatchController extends Controller
{
    public function index(): JsonResponse
    {
        $batches = InventoryBatch::query()
            ->with(['product', 'supplier', 'purchaseOrder'])
            ->latest()
            ->paginate(15);

        return response()->json($batches);
    }

    public function store(StoreInventoryBatchRequest $request): JsonResponse
    {
        $batch = InventoryBatch::create($request->validated());
        $batch->load(['product', 'supplier', 'purchaseOrder']);

        return response()->json($batch, 201);
    }

    public function show(InventoryBatch $inventory_batch): JsonResponse
    {
        $inventory_batch->load(['product', 'supplier', 'purchaseOrder']);

        return response()->json($inventory_batch);
    }

    public function update(UpdateInventoryBatchRequest $request, InventoryBatch $inventory_batch): JsonResponse
    {
        $inventory_batch->update($request->validated());
        $inventory_batch->load(['product', 'supplier', 'purchaseOrder']);

        return response()->json($inventory_batch);
    }

    public function destroy(InventoryBatch $inventory_batch): JsonResponse
    {
        $inventory_batch->delete();

        return response()->json(null, 204);
    }
}
