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
        $vehicles = User::with('driverVehicle')->find($id);
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
        $user = User::with('roles')->find($id);
        if ($user) {
            $role_id = $user->roles->first()->pivot->role_id;
            $user['role_id'] = $role_id;
            $data['vehicles'] = Vehicle::all();
            return response()->json([
                'data' => $user,
                'vehicles' => $data['vehicles'],
                'status' => 'success',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Vehicle and User Not Found',
            ], 400);
        }
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
            'vehicle_brand' => 'required',
            'model' => 'required',
            'year' => 'required',
            'license_plate' => 'required',
            'color' => 'required',
        ]);
        if (!$validator) {
            return $this->showError($validator->errors()->first());
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
                'vehicle which you add' => $vehicle,
                'data' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Vehicle Not Addded',

            ], 400);
        }
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
