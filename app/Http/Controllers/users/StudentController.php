<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ErrorController;
use App\Models\StdClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=[];
        try {
            //Getting all the students
            $students=Student::all();
            foreach($students as $s){
                $user_id=$s->user_id;
                $students_id=$s->id;
                //Parent Data
                $parent=User::select('id','first_name','last_name','email','contacts')->where('id',$user_id)->first();
                $parent_id=$user_id;
                $parent_first_name=$parent->first_name;
                $parent_second_name=$parent->last_name;
                $email=$parent->email;
                $contacts=$parent->contacts;
                //Student Data
                $student_first_name=$s->first_name;
                $student_last_name=$s->last_name;
                $reg=$s->reg;
                $term=$s->term_id;
                $year=$s->year;
                $class=StdClass::where('id',$s->class_id)->pluck('name')->first();
                array_push($data,[
                    'student_id'=>$students_id,
                    'parent_id'=>$parent_id,
                    'student_first_name'=>$student_first_name,
                    'student_last_name'=>$student_last_name,
                    'class_id'=>$class,
                    'reg'=>$reg,
                    'term_id'=>$term,
                    'year'=>$year,
                    'parent_first_name'=>$parent_first_name,
                    'parent_last_name'=>$parent_second_name,
                    'email'=>$email,
                    'contacts'=>$contacts
                ]);
            }
            return response()->json([
                'status'=>true,
                'count'=>count($data),
                'data'=>$data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return (new ErrorController())->AppError($th);
        }
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
        DB::beginTransaction();
        try {
            //Store Parent data
            $parent=User::create([
                "first_name"=>$request->parent_first_name,
                "last_name"=>$request->parent_last_name,
                "email"=>$request->email,
                "contacts"=>$request->contact,
                "password"=>Hash::make($request->password)

            ]);
            $student=Student::create([
                "user_id"=>$parent->id,
                "staff_id"=>Auth::id() || 1,
                "class_id"=>$request->class_id,
                "term_id"=>$request->term_id,
                "year"=>$request->year,
                "reg"=>$request->reg,
                "first_name"=>$request->student_first_name,
                "last_name"=>$request->student_last_name,
            ]);
             // If everything is fine, commit the transaction
             DB::commit();

             return response()->json([
                "status"=>true,
                "message"=>"Record created successfully"
             ],201);

        } catch (\Throwable $th) {
            // If there is any error, rollback the transaction
            DB::rollBack();
            
            return (new ErrorController())->AppError($th);
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
        $data=[];
        //Check if the student exists
        if(!Student::find($id)){
            return response()->json([
                'status'=>false,
                'message'=>'This Student record does not exist'
            ],404);
        }
        try {
            //Getting all the students
            $s=Student::find($id);
                $user_id=$s->user_id;
                $students_id=$s->id;
                //Parent Data
                $parent=User::select('id','first_name','last_name','email','contacts')->where('id',$user_id)->first();
                $parent_id=$user_id;
                $parent_first_name=$parent->first_name;
                $parent_second_name=$parent->last_name;
                $email=$parent->email;
                $contacts=$parent->contacts;
                //Student Data
                $student_first_name=$s->first_name;
                $student_last_name=$s->last_name;
                $reg=$s->reg;
                $term=$s->term_id;
                $year=$s->year;
                $class=StdClass::where('id',$s->class_id)->pluck('name')->first();
                array_push($data,[
                    'student_id'=>$students_id,
                    'parent_id'=>$parent_id,
                    'student_first_name'=>$student_first_name,
                    'student_last_name'=>$student_last_name,
                    'class_id'=>$class,
                    'reg'=>$reg,
                    'term_id'=>$term,
                    'year'=>$year,
                    'parent_first_name'=>$parent_first_name,
                    'parent_last_name'=>$parent_second_name,
                    'email'=>$email,
                    'contacts'=>$contacts
                ]);
            return response()->json([
                'status'=>true,
                'data'=>$data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return (new ErrorController())->AppError($th);
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
        DB::beginTransaction();
        try {
            //Update student data
            $student=Student::where('id',$id)->update([
                "class_id"=>$request->class_id,
                "term_id"=>$request->term_id,
                "year"=>$request->year,
                "reg"=>$request->reg,
                "first_name"=>$request->student_first_name,
                "last_name"=>$request->student_last_name,
            ]);
            //Store Parent data
            $parent_id=Student::where('id',$id)->pluck('user_id')->first();
            $parent=User::where('id',$parent_id)->update([
                "first_name"=>$request->parent_first_name,
                "last_name"=>$request->parent_last_name,
                "email"=>$request->email,
                "contacts"=>$request->contact
            ]);
             // If everything is fine, commit the transaction
             DB::commit();

             return response()->json([
                "status"=>true,
                "message"=>"Record updated successfully"
             ],200);

        } catch (\Throwable $th) {
            // If there is any error, rollback the transaction
            DB::rollBack();
            
            return (new ErrorController())->AppError($th);
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
        $student_id=$id;
        //Check if the student exists
        if(!Student::find($student_id)){
            return response()->json([
                'status'=>false,
                'message'=>'This Student record does not exist'
            ],404);
        }
        try {
            $parent_id=Student::where('id',$student_id)->pluck('user_id')->first();
            //Delete Student
            DB::beginTransaction();
            $del_student=Student::find($student_id)->delete();
            //Delete Parent
            $del_parent=User::find($parent_id)->delete();

            DB::commit();

            return response()->json([
               "status"=>true,
               "message"=>"Record Deleted successfully"
            ],204);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return (new ErrorController())->AppError($th);
        }
    }
}
