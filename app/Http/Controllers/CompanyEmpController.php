<?php

namespace App\Http\Controllers;

use App\Models\CompanyEmp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyEmpController extends Controller
{
    // Store employee data
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|unique:company_emps,email',
                'mobile' => 'required|string|max:10',
                'alternate_mobile' => 'nullable|string|max:10',
                'gender' => 'required|in:male,female,other',
                'address' => 'required|string|max:500',
                'guardian_name' => 'required|string|max:255',
                'relation' => 'required|string|max:100',
                'guardian_mobile' => 'required|string|max:10',
                'p_address' => 'nullable|string|max:500',
                'image' => 'nullable|file|mimes:webp,jpg,png|max:5120',
                'id_prove' => 'nullable|file|mimes:pdf|max:5120',
                'dob' => 'required|date|before:today',
            ]);

            // File uploads
            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image')->store('uploads/company_employees/images', 'public');
            }

            if ($request->hasFile('id_prove')) {
                $validatedData['id_prove'] = $request->file('id_prove')->store('uploads/company_employees/id_proofs', 'public');
            }

            $companyEmp = CompanyEmp::create($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Employee registered successfully.',
                'data' => $companyEmp,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error storing employee: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred.',
            ], 500);
        }
    }

    // Other methods like show, index, and destroy can be added similarly.
}
