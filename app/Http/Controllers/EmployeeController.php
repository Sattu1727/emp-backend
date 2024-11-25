<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    // Method to store employee data (already in your code)
    public function store(Request $request)
    {
        try {
            // Validate request data
            $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|unique:new_data_final,email',
                'mobile' => 'required|string|max:15',
                'alternate_mobile' => 'nullable|string|max:15',
                'gender' => 'required|in:male,female,other',
                'address' => 'nullable|string|max:500',
                'guardian_name' => 'nullable|string|max:255',
                'relation' => 'nullable|string|max:100',
                'guardian_mobile' => 'nullable|string|max:15',
                'g_address' => 'nullable|string|max:500',
                'image' => 'nullable|string|max:500',
                'id_prove' => 'nullable|string|max:500',
                'dob' => 'required|date|before:today',
                'token' => 'required|exists:tokens,token',
            ]);

            // Handle file uploads (if any)
            $data = $request->all();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }
            if ($request->hasFile('id_prove')) {
                $data['id_prove'] = $request->file('id_prove')->store('id_proves', 'public');
            }

            // Create employee record
            $employee = Employee::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Employee registered successfully.',
                'employee' => $employee,
            ], 201); // Created
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422); // Unprocessable Entity
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            Log::error('Database error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Database error occurred. Please try again later.',
            ], 500); // Internal Server Error
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('Unexpected error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500); // Internal Server Error
        }
    }

    // Method to get all employees
    public function index()
    {
        try {
            $employees = Employee::all();  // Retrieve all employee records

            return response()->json([
                'status' => true,
                'employees' => $employees,
            ], 200); // OK
        } catch (\Exception $e) {
            // Handle any unexpected errors
            Log::error('Error fetching employees: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching employee data.',
            ], 500); // Internal Server Error
        }
    }
}
