<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    // Store employee data
    public function store(Request $request)
    {
        try {
            $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email',
                'mobile' => 'required|string|max:15',
                'alternate_mobile' => 'nullable|string|max:15',
                'gender' => 'required|in:male,female,other',
                'address' => 'nullable|string|max:500',
                'guardian_name' => 'nullable|string|max:255',
                'relation' => 'nullable|string|max:100',
                'guardian_mobile' => 'nullable|string|max:15',
                'g_address' => 'nullable|string|max:500',
                'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'id_prove' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'dob' => 'required|date|before:today',
                'token' => 'required|exists:tokens,token',
            ]);

            $data = $request->all();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }
            if ($request->hasFile('id_prove')) {
                $data['id_prove'] = $request->file('id_prove')->store('id_proves', 'public');
            }

            $employee = Employee::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Employee registered successfully.',
                'data' => $employee,
            ], 201); // Created
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422); // Unprocessable Entity
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Database error occurred. Please try again later.',
            ], 500); // Internal Server Error
        } catch (\Exception $e) {
            Log::error('Unexpected error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500); // Internal Server Error
        }
    }

    // Get employee by ID
    public function show($id)
    {
        try {
            $employee = Employee::findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $employee,
            ], 200); // OK
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found.',
            ], 404); // Not Found
        } catch (\Exception $e) {
            Log::error('Error fetching employee by ID: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching employee data.',
            ], 500); // Internal Server Error
        }
    }

    // Get employee by full name
    public function showname($full_name)
    {
        try {
            $employee = Employee::where('full_name', $full_name)->firstOrFail();

            return response()->json([
                'status' => true,
                'data' => $employee,
            ], 200); // OK
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found.',
            ], 404); // Not Found
        } catch (\Exception $e) {
            Log::error('Error fetching employee by name: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching employee data.',
            ], 500); // Internal Server Error
        }
    }

    // Get all employees
    public function index()
    {
        try {
            $employees = Employee::all();

            return response()->json([
                'status' => true,
                'data' => $employees,
            ], 200); // OK
        } catch (\Exception $e) {
            Log::error('Error fetching employees: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching employee data.',
            ], 500); // Internal Server Error
        }
    }

    // Delete employee by ID
    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();

            return response()->json([
                'status' => true,
                'message' => 'Employee deleted successfully.',
            ], 200); // OK
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found.',
            ], 404); // Not Found
        } catch (\Exception $e) {
            Log::error('Error deleting employee: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the employee.',
            ], 500); // Internal Server Error
        }
    }
}
