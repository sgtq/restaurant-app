<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\TableLocation;
use App\Models\TableStatus;
use App\Http\Requests\TableStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tables = Table::all();
        return view('admin.tables.index', compact('tables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tables.create', $this->lists());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TableStoreRequest $request)
    {
        $table = Table::create([
            'name' => $request->name,
            'guest_number' => $request->guest_number,
            'status_id' => $request->status,
            'location_id' => $request->location,
        ]);

        return to_route('admin.tables.index')
            ->with('success', "Table $request->name created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Table $table)
    {
        return view('admin.tables.edit', array_merge(['table' => $table], $this->lists()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TableStoreRequest $request, Table $table)
    {
        $request->validate([
            'name' => 'required',
            'guest_number' => 'required',
            'status' => 'required',
            'location' => 'required',
        ]);

        $table->update([
            'name' => $request->name,
            'guest_number' => $request->guest_number,
            'status_id' => $request->status,
            'location_id' => $request->location,
        ]);

        return to_route('admin.tables.index')
            ->with('success', "Table $request->name created successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Table $table)
    {
        $table->reservations()->delete();
        $table->delete();

        return to_route('admin.tables.index')
            ->with('success', "Table $table->name deleted");
    }

    private function lists()
    {
        return [
            'tableStatuses' => TableStatus::all(['id', 'name']),
            'tableLocations' => TableLocation::all(['id', 'name'])
        ];
    }
}
