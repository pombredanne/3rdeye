<?php

namespace App\Http\Controllers;

use App\Report;
use App\Jobs\CheckForPlagiarism;
use App\Notifications\DemoCheck;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\MyMail;
use Illuminate\Support\Facades\Mail;

use Codedge\Fpdf\Fpdf\Pdf;
use \Storage;


//include 'vendor/autoload.php';
//use Smalot\PdfParser;
class ReportController extends Controller
{
    //
    
    public function demoIndex(Request $request){
        
        $report = Report::create([
            'user_id' => 0,
            'search_type' => 'demo',
            'status' => '', 'title' => '', 'character_count' => 0, 'search_mode' => "Universal",'word_count' => 0, 'sentence_count' => 0, 'matching_sentences' => 0, 'matching_sources' => 0, 'plagiarism_percentage' => 0, 'content' => '', 'search_result' => '[]', 'pdf_report' => ''
        ]);
        
        $text = $request->input('docCorpus');
        
        $dbreferences = DB::table('references')->get();     //reference corpus (universal)
        
        $pattern = '/[.\n]/';

        $documentCorpus = preg_replace('/\s+/', ' ', preg_split( '/[.]/', strtolower(preg_replace('/(\r\n|\r|\n)+/', " ", $text)))); //remove multiple spaces and replace with one space
        
        $documentCorpus = array_filter($documentCorpus, function ($obj) {
            if (str_word_count($obj) < 5) {
                return false;
            }
            $obj = strtolower($obj);
            return true;
        });  
        
        
        $dbrefarray = $dbreferences->toArray();
        
        $refIDArray = array_column($dbrefarray, 'id');
        $references = array_column($dbrefarray, 'link');
        $title = array_column($dbrefarray, 'title');
        $author = array_column($dbrefarray, 'author');
        $school = array_column($dbrefarray, 'institution');
        
        $documentCorpus = array_map('trim', $documentCorpus);
        
        $content = preg_replace('/\s+/', ' ', str_replace(array("\n", "\r", "\r\n"), ' ', $text));
        //return $documentCorpus;
        
        if(count($documentCorpus) < 1){
            return view('demo/admin', ['search_type' => 'Text', 'search_mode' => "Universal", 'title' => 'Demo','content' => $content, 'text' =>"", 'character_count' => 0, 'word_count' => 0, 'sentence_count' => 0, 'matching_sentences' => 0, 'matching_sources' => 0, 'plagiarism_percentage' => 0, 'search_result' => '[]' , 'search_result_array' => array()]);
        }

        $keywords = new \App\AhoCorasick($documentCorpus);
        
        $array = $keywords->searchIn(0, $refIDArray, $references, $title, $author, $school);
        
        //result with distinct matches
        $result = array();
        foreach($array as $row) {
            $result[$row['match']] = $row;
        }
        
        $result = array_values($result);
        $json = json_encode($result);
        
        $character_count = strlen($text);
        $word_count = str_word_count($text);
        $sentence_count = sizeof($documentCorpus);
        $matching_sentences = count(json_decode($json, true));  //number of records in json file 
        
        $plagiarised_words_count = 0;
        foreach ($result as $word) {
            $plagiarised_words_count = $plagiarised_words_count + str_word_count($word["match"]);
        }
        

        $counted = array_count_values(array_map(function($foo){return $foo['book'];}, $array));

        $matching_sources = count($counted);
        $plagiarism_percentage = round((($plagiarised_words_count / $word_count) * 100 ));
        $search_result = $json;
        $search_result_array = json_decode($json, true);    
            
        return view('demo/admin', ['search_type' => 'Text', 'title' => 'Demo','content' => $content, 'text' =>"", 'character_count' => $character_count, 'word_count' => $word_count, 'sentence_count' => $sentence_count, 'matching_sentences' => $matching_sentences, 'matching_sources' => $matching_sources, 'plagiarism_percentage' => $plagiarism_percentage, 'search_result' => $search_result , 'search_result_array' => $search_result_array]);
    }
    
    public function index(){
        
        //get data from db and send data to homepage view
        $reports = DB::table('reports')
            ->where('user_id', Auth::user()->id)->get();
        
        return view('pages/reports', ['reports' => $reports]);
    }
    
    public function view($id){  //view report from table
        //get details from database and return to view
        
        $report = DB::table('reports')->where('id', $id)->first();
        
        //process database result before returning to view
        $search_type = $report->search_type;
        $search_mode = $report->search_mode;
        $title = $report->title;
        $content = preg_replace('/\s+/', ' ', str_replace('<br />', ' ', $report->content));
        $character_count = $report->character_count;
        $word_count = $report->word_count;
        $sentence_count = $report->sentence_count;
        $matching_sentences = $report->matching_sentences;
        $matching_sources = $report->matching_sources;
        $plagiarism_percentage = $report->plagiarism_percentage;
        $search_result = $report->search_result;
        $search_result_array = json_decode($search_result, true); 
        $fullPath = $report->pdf_report;
        
        //return to view
        return view('pages.singlereporttemplate', ['id' => $id,'search_type' => $search_type, 'search_mode' => $search_mode, 'title' => $title,'content' => $content, 'text' =>"", 'character_count' => $character_count, 'word_count' => $word_count, 'sentence_count' => $sentence_count, 'matching_sentences' => $matching_sentences, 'matching_sources' => $matching_sources, 'plagiarism_percentage' => $plagiarism_percentage, 'search_result' => $search_result , 'search_result_array' => $search_result_array, 'path' => $fullPath]);
    }
    
    public function textreport(Request $request){

        $searchmode = $request->input('searchmode');
        $search_category = $request->input('category');
        $search_mode_description = "";
        

        $papertitle = $request->input('title');
        $text = $request->input('textpaper');

        $date = date('Y-m-d H:i:s');
        
        if($papertitle == null)
            $papertitle = "untitled";        //replace null title with 'untitiled'
        
        $content = filter_var(str_replace(array("\n", "\r", "\r\n"), ' ', $text), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
        
        $text = $content;
        
        $dbreferences = null;

        if($searchmode == "Universal"){
            $dbreferences = DB::table('references')->get();     //reference corpus
            $search_mode_description = 'Universal';
        }else if($searchmode == "Categorical"){
            $dbreferences = DB::table('references')->where('category', $search_category)->get();
            $search_mode_description = $searchmode.' - '.$search_category;
        }

        
        $id = DB::table('reports')->insertGetId(
            ['user_id' =>  Auth::id(), 'search_type' => 'Text', 'search_mode' => $search_mode_description, 'status' => 'pending', 'title' => $papertitle, 'content' => $content, 'character_count' => 0, 'word_count' => 0, 'sentence_count' => 0, 'matching_sentences' => 0, 'matching_sources' => 0, 'plagiarism_percentage' => 0, 'search_result' => '[]', 'pdf_report' => '', 'created_at' => $date, 'updated_at' => $date]
        );

        DB::table('searchmodes')->insert(
            ["report_id" => $id, "category" => $search_category, "search_mode_name" => $searchmode]
        );
        
        dispatch(new CheckForPlagiarism($id, $papertitle, $text, 'Text'));
        
        $pattern = '/[.\n]/';

        $documentCorpus = preg_replace('/\s+/', ' ', preg_split( '/[.]/', strtolower(preg_replace('/(\r\n|\r|\n)+/', " ", $text)))); //remove multiple spaces and replace with one space
        
        //$documentCorpus = preg_split( '/[.]/', strtolower(preg_replace('/(\r\n|\r|\n)+/', " ", $text)));
        
        $documentCorpus = array_filter($documentCorpus, function ($obj) {
            if (str_word_count($obj) < 5) {
                return false;
            }
            $obj = strtolower($obj);
            return true;
        });  
        
        
        $dbrefarray = $dbreferences->toArray();
        
        $refIDArray = array_column($dbrefarray, 'id');
        $references = array_column($dbrefarray, 'link');
        $title = array_column($dbrefarray, 'title');
        $author = array_column($dbrefarray, 'author');
        $school = array_column($dbrefarray, 'institution');
        
        $documentCorpus = array_map('trim', $documentCorpus);
        
        $content = preg_replace('/\s+/', ' ', str_replace('<br />', ' ', $content));
        //return $documentCorpus;
        
        if(count($documentCorpus) < 1){
            return view('pages.singlereporttemplate', ['id' => $id,'search_type' => 'Text', 'search_mode' => $search_mode_description,'title' => 'Demo','content' => $content, 'text' =>"", 'character_count' => 0, 'word_count' => 0, 'sentence_count' => 0, 'matching_sentences' => 0, 'matching_sources' => 0, 'plagiarism_percentage' => 0, 'search_result' => '[]' , 'search_result_array' => array(), 'path' => '']);
        }

        $keywords = new \App\AhoCorasick($documentCorpus);
        
        $array = $keywords->searchIn($id, $refIDArray, $references, $title, $author, $school);
         
        //result with distinct matches
        $result = array();
        foreach($array as $row) {
            $result[$row['match']] = $row;
        }
        
        //result with distinct books (for report)
        $book = array();
        foreach($array as $row) {
            $book[$row['book']] = $row;
        }
        
        $result = array_values($result);
        $book = array_values($book);
        
        $json = json_encode($result);
        
        $character_count = strlen($text);
        $word_count = str_word_count($text);
        $sentence_count = sizeof($documentCorpus);
        $matching_sentences = count(json_decode($json, true));  //number of records in json file 
        
        $plagiarised_words_count = 0;
        foreach ($result as $word) {
            $plagiarised_words_count = $plagiarised_words_count + str_word_count($word["match"]);
        }
        

        $counted = array_count_values(array_map(function($foo){return $foo['book'];}, $array));

        //$counted[i] as index for dbreferences array to retrieve details of sources for plagiarism
        
        $matching_sources = count($counted);
        $plagiarism_percentage = round((($plagiarised_words_count / $word_count) * 100 ));
        $search_result = $json;
        $updated_at = date('Y-m-d H:i:s');    
        $search_result_array = json_decode($json, true);    
            
        
        
        
        
        $reportLink = $this->returnDetailedPDFReport($papertitle, $plagiarism_percentage, $word_count, $sentence_count, $character_count, $matching_sentences, $matching_sources, 'Text', $date, $result, $book, $search_mode_description);
        
        $destinationPath = config('app.reportDestinationPath').'/report'.$id.'.pdf';
        
        $uploaded = Storage::put($destinationPath, $reportLink);
        $fullPath = 'http://localhost/3rdeye/resources/app/'.$destinationPath;
        
        DB::table('reports')
            ->where('id', $id)
            ->update(['status' => 'done', 'character_count' => $character_count, 'word_count' => $word_count, 'sentence_count' => $sentence_count, 'matching_sentences' => $matching_sentences, 'matching_sources' => $matching_sources, 'plagiarism_percentage' => $plagiarism_percentage, 'search_result' => $search_result, 'pdf_report' => $fullPath, 'updated_at' => $updated_at]);
        
        $percent = round($plagiarism_percentage);
        //send email 
        
        Mail::to(Auth::user()->email)
            ->queue(new \App\Mail\MyMail(Auth::user()->name, $papertitle, $percent , $word_count, $sentence_count, $character_count, $matching_sentences, $matching_sources, 'Text', $date, $fullPath));    //change send to queue
        
        
        return view('pages.singlereporttemplate', ['id' => $id,'search_type' => 'Text', 'search_mode' => $search_mode_description, 'title' => $papertitle,'content' => $content, 'text' =>"", 'character_count' => $character_count, 'word_count' => $word_count, 'sentence_count' => $sentence_count, 'matching_sentences' => $matching_sentences, 'matching_sources' => $matching_sources, 'plagiarism_percentage' => $plagiarism_percentage, 'search_result' => $search_result , 'search_result_array' => $search_result_array, 'path' => $fullPath]);
    }
    
    public function pdfreport(Request $request){
        // return $request->all();
        $parser = new \Smalot\PdfParser\Parser();
        //$parser = Parser();
        $searchmode = $request->input('uploadsearchmode');
        $search_category = $request->input('category');
        $search_mode_description = "";

        $file = $request->file('document');
        $pdfp = $parser->parseFile($file);
        $text = $pdfp->getText();
        $papertitle = $file->getClientOriginalName();
        
        $date = date('Y-m-d H:i:s');
        
        $content = filter_var(str_replace(array("\n", "\r", "\r\n"), ' ', $text), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
        
        $text = $content;
        
        $content = preg_replace('/\s+/', ' ', str_replace(array("\n", "\r", "\r\n"), " ", $text)); 
        
        $dbreferences = null;

        if($searchmode == "Universal"){
            $dbreferences = DB::table('references')->get();     //reference corpus
            $search_mode_description = 'Universal';
        }else if($searchmode == "Categorical"){
            $dbreferences = DB::table('references')->where('category', $search_category)->get();
            $search_mode_description = $searchmode.' - '.$search_category;
        }
        
        $id = DB::table('reports')->insertGetId(
            ['user_id' =>  Auth::id(), 'search_type' => 'PDF', 'status' => 'pending', 'title' => $papertitle, 'search_mode' => $search_mode_description, 'content' => $content, 'character_count' => 0, 'word_count' => 0, 'sentence_count' => 0, 'matching_sentences' => 0, 'matching_sources' => 0, 'plagiarism_percentage' => 0, 'search_result' => '[]', 'pdf_report' => '', 'created_at' => $date, 'updated_at' => $date]
        );
        
        DB::table('searchmodes')->insert(
            ["report_id" => $id, "category" => $search_category, "search_mode_name" => $searchmode]
        );

        dispatch(new CheckForPlagiarism($id, $papertitle, $text, 'PDF'));

        $pattern = '/[.\n]/';

        //$documentCorpus = preg_split( '/[.]/', strtolower(preg_replace('/(\r\n|\r|\n)+/', "", $text)));
        $documentCorpus = preg_replace('/\s+/', ' ', preg_split( '/[.]/', strtolower(preg_replace('/(\r\n|\r|\n)+/', "", $text)))); //remove multiple spaces and replace with one space
        
        $documentCorpus = array_filter($documentCorpus, function ($obj) {
            if (str_word_count($obj) < 5) {
                return false;
            }
            $obj = strtolower($obj);
            return true;
        });  
        
        
        $dbrefarray = $dbreferences->toArray();
            
        $refIDArray = array_column($dbrefarray, 'id');    
        $references = array_column($dbrefarray, 'link');
        $title = array_column($dbrefarray, 'title');
        $author = array_column($dbrefarray, 'author');
        $school = array_column($dbrefarray, 'institution');
        
        $documentCorpus = array_map('trim', $documentCorpus);
        
        //return $documentCorpus;
        
        if(count($documentCorpus) < 1){
            return view('pages.singlereporttemplate', ['id' => $id,'search_type' => 'PDF', 'search_mode' => $search_mode_description,'title' => $papertitle, 'content' => $content, 'text' =>"", 'character_count' => 0, 'word_count' => 0, 'sentence_count' => 0, 'matching_sentences' => 0, 'matching_sources' => 0, 'plagiarism_percentage' => 0, 'search_result' => '[]' , 'search_result_array' => array()]);
        }

        $keywords = new \App\AhoCorasick($documentCorpus);
        
        //$array = $keywords->searchIn($references, $title, $author, $school);
        $array = $keywords->searchIn($id, $refIDArray, $references, $title, $author, $school);
         
        $result = array();
        
        //result with distinct matches
        foreach($array as $row) {
            $result[$row['match']] = $row;
        }
        $result = array_values($result);
        
        //result with distinct books (for report)
        $book = array();
        foreach($array as $row) {
            $book[$row['book']] = $row;
        }
        $book = array_values($book);
        
        $json = json_encode($result);
        
        $character_count = strlen($text);
        $word_count = str_word_count($text);
        $sentence_count = sizeof($documentCorpus);
        $matching_sentences = count(json_decode($json, true));  //number of records in json file 
        
        $plagiarised_words_count = 0;
        foreach ($result as $word) {
            $plagiarised_words_count = $plagiarised_words_count + str_word_count($word["match"]);
        }
        
        $counted = array_count_values(array_map(function($foo){return $foo['book'];}, $array));
        
        $matching_sources = count($counted);
        $plagiarism_percentage = round((($plagiarised_words_count / $word_count) * 100 ));
        $search_result = $json;
               
        $updated_at = date('Y-m-d H:i:s');    
        $search_result_array = json_decode($json, true);    
            
        $reportLink = $this->returnDetailedPDFReport($papertitle, $plagiarism_percentage, $word_count, $sentence_count, $character_count, $matching_sentences, $matching_sources, 'PDF', $date, $result, $book, $search_mode_description);
        
        $destinationPath = config('app.reportDestinationPath').'/report'.$id.'.pdf';
        
        $uploaded = Storage::put($destinationPath, $reportLink);
        $fullPath = 'http://localhost/3rdeye/resources/app/'.$destinationPath;
            
        DB::table('reports')
            ->where('id', $id)
            ->update(['status' => 'done', 'character_count' => $character_count, 'word_count' => $word_count, 'sentence_count' => $sentence_count, 'matching_sentences' => $matching_sentences, 'matching_sources' => $matching_sources, 'plagiarism_percentage' => $plagiarism_percentage, 'search_result' => $search_result, 'pdf_report' => $fullPath, 'updated_at' => $updated_at]);
        
       
        $percent = round($plagiarism_percentage);
        //send email 
        
        Mail::to(Auth::user()->email)
            ->send(new \App\Mail\MyMail(Auth::user()->name, $papertitle, $percent , $word_count, $sentence_count, $character_count, $matching_sentences, $matching_sources, 'PDF', $date, $fullPath));
        
        
        
        //return view
        return view('pages.singlereporttemplate', ['id' => $id,'search_type' => 'PDF', 'search_mode' => $search_mode_description, 'title' => $papertitle,'content' => $content, 'text' =>"", 'character_count' => $character_count, 'word_count' => $word_count, 'sentence_count' => $sentence_count, 'matching_sentences' => $matching_sentences, 'matching_sources' => $matching_sources, 'plagiarism_percentage' => $plagiarism_percentage, 'search_result' => $search_result , 'search_result_array' => $search_result_array, 'path' => $fullPath]);
    }
    
    public function returnDetailedPDFReport($papertitle, $plagiarism_percentage, $word_count, $sentence_count, $character_count, $matching_sentences, $matching_sources, $search_type, $upload_time, $search_result_array, $book, $searchmode){
        
        $pdf = new PDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial','I',40);
        $pdf->Cell(195,120,'PDF REPORT',0,0,'C');

        $pdf->AddPage();

        $pdf->SetFont('Arial','B',20);
        $pdf->Cell(80);
        // Title
        $pdf->Cell(30,30,'REPORT INFO',0,0,'C');
        // Line break
        $pdf->Ln(40);
        $pdf->SetFont('Helvetica','',10);
        //move left

        $pdf->Cell(30,10,'Report Title:',0,0,'L');
        $pdf->Cell(20);
        $pdf->Cell(30,10,$papertitle,0,0,'L');
        $pdf->Ln(6);

        $pdf->Cell(30,10,'Score:',0,0,'L');
        $pdf->Cell(20);
        $pdf->Cell(30,10,$plagiarism_percentage.'%',0,0,'L');
        $pdf->Ln(6);

        $pdf->Cell(30,10,'Word Count:',0,0,'L');
        $pdf->Cell(20);
        $pdf->Cell(30,10,$word_count,0,0,'L');
        $pdf->Ln(6);

        $pdf->Cell(30,10,'Sentence Count:',0,0,'L');
        $pdf->Cell(20);
        $pdf->Cell(30,10,$sentence_count,0,0,'L');
        $pdf->Ln(6);

        $pdf->Cell(30,10,'Character Count:',0,0,'L');
        $pdf->Cell(20);
        $pdf->Cell(30,10,$character_count,0,0,'L');
        $pdf->Ln(6);

        $pdf->Cell(30,10,'Matching Sentences:',0,0,'L');
        $pdf->Cell(20);
        $pdf->Cell(30,10,$matching_sentences,0,0,'L');
        $pdf->Ln(6);

        $pdf->Cell(30,10,'Matching Sources:',0,0,'L');
        $pdf->Cell(20);
        $pdf->Cell(30,10,$matching_sources,0,0,'L');
        $pdf->Ln(6);

        $pdf->Cell(30,10,'Search Type:',0,0,'L');
        $pdf->Cell(20);
        $pdf->Cell(30,10,$search_type,0,0,'L');
        $pdf->Ln(6);

        $pdf->Cell(30,10,'Search Mode:',0,0,'L');
        $pdf->Cell(20);
        $pdf->Cell(30,10,$searchmode,0,0,'L');
        $pdf->Ln(6);

        $pdf->Cell(30,10,'Upload Time:',0,0,'L');
        $pdf->Cell(20);
        $pdf->Cell(30,10,$upload_time,0,0,'L');
        $pdf->Ln(5);

        $pdf->AddPage();

        $pdf->SetFont('Arial','B',20);
        $pdf->Cell(80);
        // Title
        $pdf->Cell(30,30,'ALL MATCHES',0,0,'C');
        // Line break
        $pdf->Ln(40);
        //set color to say Match 1

        $matchno = 1;
        
        foreach ($search_result_array as $result) {
            
            //matches (use for/foreach loop here)
            $pdf->SetFont('Helvetica','',10);
            $pdf->Cell(30,10,'MATCH '.$matchno,0,0,'L');
            $pdf->Ln(10);
            $pdf->SetFont('Helvetica','I',10);
            $pdf->SetTextColor(255,0,0);
            //move left

            $pdf->MultiCell(190,5,'"'.ucwords($result["match"]).'"');
            $pdf->SetTextColor(0,0,0);
            $pdf->MultiCell(190,5, 'Appears to be copied from Page '.$result["page"].' of "'.$result["title"].'" by '.ucwords($result["author"]));
            $pdf->Ln(10);
            
            $matchno++;
        }



        //last page with result
        $pdf->AddPage();
        $pdf->SetFont('Arial','',9);
        $pdf->SetTextColor(255,0,0);
        // Title

        //draw line
        $pdf->SetLineWidth(0.1);
        $pdf->SetDash(); //restores no dash
        $pdf->Line(10,41,190,41);

        $pdf->Cell(30,30,'ORIGINALITY REPORT',0,0,'L');

        //draw line
        $pdf->SetDash(); //restores no dash
        $pdf->Line(10,48,190,48);
        $pdf->SetDash(); //restores no dash
        $pdf->Line(10,87,190,87);
        $pdf->SetDash(); //restores no dash
        $pdf->Line(10,94,190,94);


        $pdf->Ln(25);
  
        if($plagiarism_percentage > 30)
            $pdf->SetTextColor(255,0,0);
        else if ($plagiarism_percentage > 10)
            $pdf->SetTextColor(128,0,64);
        else $pdf->SetTextColor(34,139,34);


        $pdf->SetFont('Helvetica','',50);
        $pdf->Cell(30,10,$plagiarism_percentage."%",0,0,'L');

        //move 4 centimeters to the right (for internet sources and student thesis)
        $pdf->SetFont('Helvetica','',30);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(40);
        $pdf->Cell(30,10,'0%',0,0,'L');

        $pdf->Cell(30);
        $pdf->Cell(30,10,$plagiarism_percentage."%",0,0,'L');

        $pdf->Ln(1);

        $pdf->SetFont('Arial','',10);    //new font and color for "similarity index" ....don't forget to draw new line

        // Title
        $pdf->Cell(30,30,'PLAGIARISM PERCENTAGE',0,0,'L');
        $pdf->Cell(40);
        $pdf->Cell(30,30,'OTHER SOURCES',0,0,'L');
        $pdf->Cell(30);
        $pdf->Cell(30,30,'STUDENT THESIS',0,0,'L');
        // Line break
        $pdf->Ln(20);
        $pdf->SetFont('Arial','',9);
        $pdf->SetTextColor(255,0,0);
        // Title

        //draw line
        $pdf->Cell(30,30,'PRIMARY SOURCES',0,0,'L');
        $pdf->Ln(30);

        //draw line

        //here, we list all the sources using the for or foreach loop
        foreach ($book as $b) {
            
            $pdf->SetFont('Arial','',23);
            $pdf->SetTextColor(255,0,0);
            $pdf->MultiCell(190,5,'Submitted to '.$b["school"][0]);
            $pdf->Ln(2);
            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(100,100,100);
            $pdf->Cell(30,10,"STUDENT THESIS",0,0,'L');
            $pdf->Ln(10);
            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(100,100,100);
            $pdf->MultiCell(190,5,"TITLE: ".strtoupper($b["title"]));
            $pdf->SetFont('Arial','',10);
            $pdf->Ln(2);
            $pdf->MultiCell(190,5,"AUTHOR: ".strtoupper($b["author"]));
            $pdf->Ln(8);
        }

        return $pdf->Output('S');

        
    }
    
    public function destroy($id) {
      
        DB::delete('delete from reports where id = ?',[$id]);
        return redirect('/reports');
        
    }
}
