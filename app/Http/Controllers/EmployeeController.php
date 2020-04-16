<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return response()->json([
                'status'    => 'success',
                'result'    => Employee::get()->toArray()
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => 'fail',
                'messages'  => 'Something Went Wrong!'
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'salary' => 'required',
            'age' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $post = $request->all();
        try {
            $employee = Employee::create($post);
            return response()->json([
                'status'    => 'success',
                'result'    => $employee
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => 'fail',
                'messages'  => 'Something Went Wrong!'
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
            return response()->json([
                'status'    => 'success',
                'result'    => Employee::find($id)
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => 'fail',
                'messages'  => 'Something Went Wrong!'
            ], 400);
        }
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
        $post = $request->all();
        try {
            Employee::where('id', $id)->update($post);
            return response()->json([
                'status'    => 'success',
                'result'    => Employee::find($id)
            ], 202);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => 'fail',
                'messages'  => 'Something Went Wrong!'
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
            Employee::where('id', $id)->delete();
            return response()->json([
                'status'    => 'success',
                'messages'  => 'Success Delete!'
            ], 202);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => 'fail',
                'messages'  => 'Something Went Wrong!'
            ], 400);
        }
    }
}
