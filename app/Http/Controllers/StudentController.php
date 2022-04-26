<?php

namespace App\Http\Controllers;

use App\Models\Student;
use http\Env\Response;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return view('student.index');
    }

    public function storeStudents(Request $request)
    {
        $student = new Student;
        $student->user_name = $request->user_name;
        $student->email = $request->email;
        $student->save();
        return response()->json([
           'message' => 'Student Added'
        ]);
    }
    public function getStudents()
    {
        return response()->json([
           'students' => Student::all()
        ]);
    }

    public function editStudent(Request $request)
    {
        $student = Student::find($request->id);
        if($student){
            return response()->json([
               'status' => 200,
               'student' => $student
            ]);
        }
        else{
            return response()->json([
               'status' => 404,
               'message' => 'Student not found'
            ]);
        }
    }

    public function updateStudent(Request $request)
    {

        $success = false;
        $student = Student::find($request->id);
        Student::where("id", $request->id)
            ->update([
                'user_name' => $request->user_name,
                'email' => $request->email,
            ]);



        if($student) {
            $success = true;
        }

        return response()->json(['message' => 'Student updated','success' => $success, 'student' => $student]);
    }

    public function deleteStudent(Request $request)
    {
        $success = false;
        $student = Student::destroy($request->id);
        if($student) {
            $success = true;
        }

        return  response()->json(['success' => $success, 'message' => 'Student deleted']);
        }
    }
