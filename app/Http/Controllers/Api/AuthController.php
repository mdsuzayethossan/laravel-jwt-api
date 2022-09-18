<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::all();
        return response()->json([
            'success' => true,
            'message' => 'Display all users',
            'data' => $data,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => ['required', Password::min(8)],
                'password_confirm' => 'required|same:password'

        ]);
        if($validator->fails()){
            return response()->json([
                'success'=> false,
                'errors' => $validator->errors(),
            ], 401);
        }
        try {
           $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            return response()->json([
                'success'=> true,
                'message' => 'User created successfully',
                'user_data' => $user,
            ], 200);
        }
        catch(Exception $e) {
            return response()->json([
                'success'=> false,
                'errors' => 'Something wrong.....',
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
        try {
            return User::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'User Displayed',
            ]);
        }
        catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ]);

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
        $validator = validator::make($request->all(), [
            'name' => 'required',
            'password' => ['required', Password::min(8)],
            'password_confirm' => 'required|same:password'

    ]);
    if($validator->fails()){
        return response()->json([
            'success'=> false,
            'errors' => $validator->errors(),
        ], 401);
    }
    try {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json([
            'success'=> true,
            'message' => 'User Updated successfully',
            'user_data' => $user,
        ], 200);
    }
    catch(Exception $e) {
        return response()->json([
            'success'=> false,
            'errors' => 'Something wrong.....',
        ], 400);
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            return User::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);

        }
        catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went worng'
            ]);

        }
    }
}
