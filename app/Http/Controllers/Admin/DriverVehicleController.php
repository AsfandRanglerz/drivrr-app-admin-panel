<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DriverVehicle;
use App\Models\Vehicle;


class DriverVehicleController extends Controller
{
    public function index(Request $request, $id)
    {
        $data = User::with('driverVehicle.vehicle')->find($id);
        // return $data;
        return view('admin.driver.vehicle.index', compact('data'));
    }

    public function create(Request $request, $id)
    {
        $data['user'] = User::find($id);
        // return $data;
        $data['vehicles'] = Vehicle::all();
        //  $response = response()->json($vehicles);
        return view('admin.driver.vehicle.create', compact('data'));
    }

    public function store(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'vehicle_brand' => 'required',
            'model' => 'required',
            'year' => 'required',
            'license_plate' => 'required',
            'color' => 'required',
        ]);

        $vehicle = DriverVehicle::create([
            'user_id' => $id,
            'vehicle_id' => $request->vehicle_id,
            'vehicle_brand' => $request->vehicle_brand,
            'model' => $request->model,
            'year' => $request->year,
            'license_plate' => $request->license_plate,
            'color' => $request->color,
        ]);

        // return $vehicle;
        return redirect()->route('driver-vehicle.index', $id)->with(['status' => true, 'message' => 'Vehicle Added successfully.']);
    }
    public function edit($id)
    {
        $data['details'] = DriverVehicle::find($id);
        $data['vehicle'] = Vehicle::all();
        return view('admin.driver.vehicle.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'vehicle_brand' => 'required',
            'model' => 'required',
            'year' => 'required',
            'license_plate' => 'required',
            'color' => 'required',
        ]);

        $vehicle = DriverVehicle::find($id);
        $vehicle->vehicle_id = $request->vehicle_id;
        $vehicle->vehicle_brand = $request->vehicle_brand;
        $vehicle->model = $request->model;
        $vehicle->year = $request->year;
        $vehicle->license_plate = $request->license_plate;
        $vehicle->color = $request->color;
        $vehicle->update();
        return redirect()->route('driver-vehicle.index', $vehicle->user_id)->with(['status' => true, 'message' => 'Vehicle Updated successfully.']);
    }

    public function destroy($id)
    {
        DriverVehicle::destroy($id);
        return redirect()->back()->with(['status' => true, 'message' => 'Vehicle deleted Successfully.']);
    }

    public function status($id)
    {
        $data = DriverVehicle::find($id);

        // Toggle the 'is_active' state of the clicked record
        $newState = $data->is_active === '1' ? '0' : '1';
        $data->update(['is_active' => $newState]);

        // If the record is being set as active, deactivate all others
        if ($newState === '1') {
            DriverVehicle::where('id', '!=', $id)->update(['is_active' => '0']);
        }
        return redirect()->back()->with(['status' => true, 'message' => 'Status Updated successfully']);
    }
}
