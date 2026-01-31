<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Models\PurchaseOrder;
use Illuminate\Http\JsonResponse;

class PurchaseOrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = PurchaseOrder::query()
            ->with(['supplier', 'items.product', 'createdByUser'])
            ->latest()
            ->paginate(15);

        return response()->json($orders);
    }

    public function store(StorePurchaseOrderRequest $request): JsonResponse
    {
        $order = PurchaseOrder::create([
            'supplier_id' => $request->supplier_id,
            'order_date' => $request->order_date,
            'status' => $request->input('status', 'pending'),
            'created_by' => $request->user()?->id,
        ]);

        foreach ($request->items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'cost_price' => $item['cost_price'],
            ]);
        }

        $order->load(['supplier', 'items.product', 'createdByUser']);

        return response()->json($order, 201);
    }

    public function show(PurchaseOrder $purchase_order): JsonResponse
    {
        $purchase_order->load(['supplier', 'items.product', 'createdByUser']);

        return response()->json($purchase_order);
    }

    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchase_order): JsonResponse
    {
        $orderData = collect($request->validated())->except('items')->filter()->all();
        if (! empty($orderData)) {
            $purchase_order->update($orderData);
        }

        if ($request->has('items')) {
            $purchase_order->items()->delete();
            foreach ($request->items as $item) {
                $purchase_order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                ]);
            }
        }

        $purchase_order->load(['supplier', 'items.product', 'createdByUser']);

        return response()->json($purchase_order);
    }

    public function destroy(PurchaseOrder $purchase_order): JsonResponse
    {
        $purchase_order->delete();

        return response()->json(null, 204);
    }
}
