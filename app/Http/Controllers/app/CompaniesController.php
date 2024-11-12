<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $label="Companies";

        $companies=Company::all();
        return view('app.admin.designation',compact('label','companies'));
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
        try {
            //code...
            Company::create([
                'name'=>$request->name,
                'month1'=>$request->m1,
                'month2'=>$request->m2,
                'month3'=>$request->m3,
                'month4'=>$request->m4,
                'month5'=>$request->m5,
                'month6'=>$request->m6,
                'month7'=>$request->m7,
                'month8'=>$request->m8,
                'month9'=>$request->m9,
                'month10'=>$request->m10,
                'month11'=>$request->m11,
                'month12'=>$request->m12,
            ]);
    
            session()->flash('message','Company Added Successfully');
            return back();
        } catch (\Throwable $th) {
            //throw $th;
            if(str_contains($th->getMessage(),"1062 Duplicate entry")){
                session()->flash('error','Duplicate entry for comapny name');
                return back();
            }else{
                session()->flash('message','An error occured, please try again later');
                return back();
            }
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
        try {        
            Company::where('id',$id)->update([
                'name'=>$request->name,
                'month1'=>$request->m1,
                'month2'=>$request->m2,
                'month3'=>$request->m3,
                'month4'=>$request->m4,
                'month5'=>$request->m5,
                'month6'=>$request->m6,
                'month7'=>$request->m7,
                'month8'=>$request->m8,
                'month9'=>$request->m9,
                'month10'=>$request->m10,
                'month11'=>$request->m11,
                'month12'=>$request->m12,
            ]);

            session()->flash('message','Data Updated Successfully');
            return back();
        } catch (\Throwable $th) {
            //throw $th;
            if(str_contains($th->getMessage(),"1062 Duplicate entry")){
                session()->flash('error','Duplicate entry for comapny name');
                return back();
            }else{
                session()->flash('message','An error occured, please try again later');
                return back();
            }
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
        //
    }
}
