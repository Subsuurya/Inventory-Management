<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalesOrderRequest;
use App\Http\Requests\UpdateSalesOrderRequest;
use App\Models\InventoryBatch;
use App\Models\InventoryMovement;
use App\Models\SalesOrder;
use Illuminate\Http\JsonResponse;

class SalesOrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = SalesOrder::query()
            ->with(['customer', 'items.product', 'items.inventoryBatch', 'createdByUser'])
            ->latest()
            ->paginate(15);

        return response()->json($orders);
    }

    public function store(StoreSalesOrderRequest $request): JsonResponse
    {
        $order = SalesOrder::create([
            'customer_id' => $request->customer_id,
            'sale_date' => $request->sale_date,
            'created_by' => $request->user()?->id,
        ]);

        foreach ($request->items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'inventory_batch_id' => $item['inventory_batch_id'],
                'quantity' => $item['quantity'],
                'selling_price' => $item['selling_price'],
            ]);
            $this->recordSaleOut(
                (int) $item['product_id'],
                (int) $item['inventory_batch_id'],
                (float) $item['quantity'],
                $order->id
            );
        }

        $order->load(['customer', 'items.product', 'items.inventoryBatch', 'createdByUser']);

        return response()->json($order, 201);
    }

    public function show(SalesOrder $sales_order): JsonResponse
    {
        $sales_order->load(['customer', 'items.product', 'items.inventoryBatch', 'createdByUser']);

        return response()->json($sales_order);
    }

    public function update(UpdateSalesOrderRequest $request, SalesOrder $sales_order): JsonResponse
    {
        $orderData = collect($request->validated())->except('items')->filter()->all();
        if (! empty($orderData)) {
            $sales_order->update($orderData);
        }

        if ($request->has('items')) {
            foreach ($sales_order->items as $oldItem) {
                InventoryBatch::where('id', $oldItem->inventory_batch_id)
                    ->increment('quantity_remaining', (float) $oldItem->quantity);
            }
            $sales_order->items()->delete();
            foreach ($request->items as $item) {
                $sales_order->items()->create([
                    'product_id' => $item['product_id'],
                    'inventory_batch_id' => $item['inventory_batch_id'],
                    'quantity' => $item['quantity'],
                    'selling_price' => $item['selling_price'],
                ]);
                $this->recordSaleOut(
                    (int) $item['product_id'],
                    (int) $item['inventory_batch_id'],
                    (float) $item['quantity'],
                    $sales_order->id
                );
            }
        }

        $sales_order->load(['customer', 'items.product', 'items.inventoryBatch', 'createdByUser']);

        return response()->json($sales_order);
    }

    public function destroy(SalesOrder $sales_order): JsonResponse
    {
        $sales_order->delete();

        return response()->json(null, 204);
    }

    private function recordSaleOut(int $productId, int $batchId, float $quantity, int $salesOrderId): void
    {
        $batch = InventoryBatch::findOrFail($batchId);
        $batch->decrement('quantity_remaining', $quantity);

        InventoryMovement::create([
            'product_id' => $productId,
            'inventory_batch_id' => $batchId,
            'movement_type' => 'OUT',
            'quantity' => -$quantity,
            'reference_type' => 'sales_order',
            'reference_id' => $salesOrderId,
        ]);
    }
}
