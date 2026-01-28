<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDummyTableRequest;
use App\Http\Requests\UpdateDummyTableRequest;
use App\Models\DummyTable;
use Illuminate\Http\JsonResponse;

class DummyTableController extends Controller
{
    public function index(): JsonResponse
    {
        $dummyTables = DummyTable::query()
            ->latest()
            ->paginate(15);

        return response()->json($dummyTables);
    }

    public function store(StoreDummyTableRequest $request): JsonResponse
    {
        $dummyTable = DummyTable::create($request->validated());

        return response()->json($dummyTable, 201);
    }

    public function show(DummyTable $dummy_table): JsonResponse
    {
        return response()->json($dummy_table);
    }

    public function update(UpdateDummyTableRequest $request, DummyTable $dummy_table): JsonResponse
    {
        $dummy_table->update($request->validated());

        return response()->json($dummy_table);
    }

    public function destroy(DummyTable $dummy_table): JsonResponse
    {
        $dummy_table->delete();

        return response()->json(null, 204);
    }
}

