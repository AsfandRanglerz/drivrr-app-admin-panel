<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DriverVehicle;
use Illuminate\Support\Facades\Validator;
use App\Models\Vehicle;

class DriverVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $vehicles = User::with('driverVehicle', 'roles')->find($id);
        if ($vehicles) {
            $role_id = $vehicles->roles->first()->pivot->role_id;
            $vehicles['role_id'] = $role_id;
            return response()->json([
                'data' => $vehicles,
                'status' => 'success',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Vehicle with User Not Found',

            ], 400);
        }
    }
    public function getVehicles()
    {
        $vehicles = Vehicle::all();
        return response()->json([
            'Vehicles' => $vehicles,
            'status' => 'success',
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required',
            'vehicle_brand' => 'required',
            'model' => 'required',
            'year' => 'required',
            'license_plate' => 'required',
            'color' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }
        $user = User::with('roles')->find($id);
        if ($user) {
            $role_id = $user->roles->first()->pivot->role_id;
            $user['role_id'] = $role_id;
            $vehicle = DriverVehicle::create([
                'user_id' => $id,
                'vehicle_id' => $request->vehicle_id,
                'vehicle_brand' => $request->vehicle_brand,
                'model' => $request->model,
                'year' => $request->year,
                'license_plate' => $request->license_plate,
                'color' => $request->color,

            ]);
            return response()->json([
                'message' => 'Vehicle Added successfully.',
                'status' => 'success.',
                'driver_vehicles' => $vehicle,
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    }
    public function updateStatus($id)
    {
        $vehicle = DriverVehicle::findOrFail($id);

        $vehicle->is_active = $vehicle->is_active == '0' ? '1' : '0';

        $vehicle->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'data' => $vehicle,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $user = User::with('driverVehicle', 'roles')->find($id);

        if ($user) {
            $role_id = $user->roles->first()->pivot->role_id;
            $user['role_id'] = $role_id;
            $data = [
                'user' => $user,
                'driverVehicle' => $user->driverVehicle,
            ];

            return response()->json([
                'data' => $data,
                'status' => 'success',
            ], 200);
        } else {
            return response()->json([
                'message' => 'User Not Found',
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
