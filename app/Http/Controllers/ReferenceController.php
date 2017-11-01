<?php

namespace App\Http\Controllers;

use App\Reference;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Storage;
use Illuminate\Support\Facades\Auth; 

class ReferenceController extends Controller
{
    //only admins have access to this controller 
    
    public function index(){
        
        if (Auth::user()->type != 'Admin'){
           return redirect('/'); 
        }
        
        //get data from db and send data to homepage view
        $references = Reference::all();
        return view('pages/references', ['references' => $references]);
    }
    
    public function destroy($id) {
      
        if (Auth::user()->type != 'Admin'){
           return redirect('/'); 
        }
        
        $reference = DB::table('references')->select('references.link')->where('id', $id)->get();
        DB::table('references')->where('id', $id)->delete();
       
        $reference = basename(str_replace(array("}","]", '"', "'"), '', $reference));
        $path = config('app.fileDestinationPath').'/'.$reference;
        
        try{
          Storage::delete($path);  
        }catch(Exception $e){}
        
        return redirect('/references');
    }
    
    public function showDetails($id){
        
        if (Auth::user()->type != 'Admin'){
           return redirect('/'); 
        }
        
        $reference = DB::table('references')->where('id', $id)->first();
        
        if (empty($reference)) {
            // list is empty.
            return redirect()->back();
        }else{
            return view('pages.viewref')->with('reference', $reference); 
        }
    }
    
    public function update(Request $request, $id){
        
        if (Auth::user()->type != 'Admin'){
           return redirect('/'); 
        }
        
        //get the link of old path incase we are updating pdf to prevent loss of information
        $oldPDFLink = DB::table('references')->select('references.link')->where('id', $id)->get();
        $oldPDFLink = basename(str_replace(array("}","]", '"', "'"), '', $oldPDFLink));
        $path = config('app.fileDestinationPath').'/'.$oldPDFLink;
        
        //update all fields except the file
        DB::table('references')->where('id', $id)->update(['title' => $request->title, 'category' => $request->category, 'author' => $request->author, 'institution' => $request->institution]);
        
        //update file in DB only if there is file input 
        if( $request->hasFile('pdfupload') && $request->file('pdfupload')->isValid()){
            
            try{
              Storage::delete($path);  
            }catch(Exception $e){}
            $newpath = $request->file('pdfupload')->store(config('app.fileDestinationPath'));
            $fullPath = 'http://localhost/3rdeye/resources/app/'.$newpath;
            DB::table('references')->where('id', $id)->update(['link' => $fullPath]);
            
        } 
        return redirect('/references');
    }
    
    
    public function insert(){
        
        if (Auth::user()->type != 'Admin'){
           return redirect('/'); 
        }

        $categories = DB::table('categories')->get(); 
        $institutions = DB::table('institutions')->get(); 

        return view('pages/addref', ['categories' => $categories, 'institutions' => $institutions]);
    }
    
    
    public function save(Request $request){
        
        if (Auth::user()->type != 'Admin'){
           return redirect('/'); 
        }

        $date = date('Y-m-d H:i:s');
        //$request->all();
        $title = $request->input('title');
        $author = $request->input('author');
        $category = $request->input('category');
        $supervisor = $request->input('supervisor');
        $institution = $request->input('institution');
        
        //$file = $request->file('pdfupload');    //pdf to upload
        
        //$fileName = $file->getClientOriginalName();
        //$destinationPath = config('app.fileDestinationPath').'/'.$fileName;
        
        //$uploaded = Storage::put($destinationPath, file_get_contents($file->getRealPath()));
        
        $path = $request->file('pdfupload')->store(config('app.fileDestinationPath'));
        //return $path;
        
        $fullPath = 'http://localhost/3rdeye/resources/app/'.$path;
        
        DB::table('references')->insert(
            ['title' => $title, 'author' => $author, 'category' => $category, 'supervisor' => $supervisor, 'institution' => $institution, 'link' => $fullPath, 'created_at' => $date, 'updated_at' => $date]
        );
        
        return redirect('/references');
    }
}
