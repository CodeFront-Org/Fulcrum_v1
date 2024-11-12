<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $media=Media::all();
        if(count($media)>0){
            return $media;
        }else{
            return response()->json(["status"=>false,"error"=>"No File Found"],404);
        }
    }

    public function download(Request $request){
        $fileId = $request->query('fileId');
        $media = Media::find($fileId);
    
        if (!$media) {
            return response()->json(['status' => false, 'error' => 'File not found.'], 404);
        }
    
        $path = public_path('images/single/' . $media->path);
    
        if (!file_exists($path)) {
            return response()->json(['status' => false, 'error' => 'File not found.'], 404);
        }
    
        $headers = [
            'Content-Type' => mime_content_type($path), // Set the appropriate content type
        ];
    
        return response()->download($path, "file_$fileId." . $media->type, $headers);
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
        $type=$request->type;
        if($type==1){//Upload Single File
                $file= $request->file("file"); //Handler to be used in file operations
                  if($file){  //Check if form was submitted with the file
                      //Delete Old Image if it exists
                      $id=1;
                      if(Media::where('staff_id',$id)->where('path',$file->getClientOriginalName())->exists()){  //Check if doc exists
                        
                          $get_file=$file->getClientOriginalName(); //Get file_name or path of file in db column defined as path so that you can define it in url below
                          $url='images/single/'.$get_file;
                          if(file_exists($url)){
                              @unlink($url); //Removes the file
                          }
                          Media::where('staff_id',$id)->where('path',$file->getClientOriginalName())->delete();
                      }
                      //Add New File image
                      //Getting other files params
                        // Getting the original file name
                        $fileName=time()."_".$id."_".$file->getClientOriginalName();

                        // Getting the file type (extension)
                        $fileType = $file->getClientOriginalExtension();

                        // Getting the file size in kilobytes
                        $fileSize = $file->getSize(); // Returns size in bytes
                        $fileSizeKB = $fileSize / 1024; // Convert bytes to kilobytes

                      $file->move('images/single/',$fileName);
                      $input['path']=$fileName;
                      //Put Updated data to Database
                      $path=$input['path'];
                      $media=Media::create([
                        'staff_id'=>1,
                        'name'=>$fileName,
                        'type'=>$fileType,
                        'size'=>$fileSizeKB,
                        'path'=>$path,
                      ]);
                      if($media){
                        return response()->json(['status'=>true,'msg'=>'File Uploaded Successfully.'],200);
                      }else{
                        return response()->json(['status'=>false,'error'=>'An unexpected error occurred. Please try again later.'],500);
                      }
                   }else{  // Here no file was submitted with the form or no upload made in the frontend
          
                      //return "no file uploads";
          
                  }
        }elseif($type==2){//Upload Multiple Files
            return "Multiple";
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
