<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with('activeBorrowings');

        if ($request->has('search')) {
            $search  = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $search  = $request->search;
            $query->where('full_name', 'like', "%{$search}%");
        }

        $students = $query->paginate(10);
        return StudentResource::collection($students);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $student = Student::create($request->validated());
        $student->load('activeBorrowings');
        return new StudentResource($student);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['activeBorrowings', 'borrowings']);
        return new StudentResource($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());
        return new StudentResource($student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $student = Student::findOrFail($id);

            if ($student->activeBorrowings()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot delete with active borrowings'
                ], 422);
            }
            $student->delete();
            return response()->json([
                'message' => 'Student deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'No data found'
            ]);
        }
    }
}
