<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Report;

use Illuminate\Support\Facades\DB;

use Codedge\Fpdf\Fpdf\Pdf;
use \Storage;

class CheckForPlagiarism implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    protected $mytext, $id, $papertitle;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $papertitle, $mytext, $searchType)
    {
        //
        $this->mytext = $mytext;
        $this->id = $id;
        $this->papertitle = $papertitle;
        $this->searchType = $searchType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $text = $this->mytext;
        $id = $this->id;
        $papertitle = $this->papertitle;
        $searchType = $this->searchType;
        
        $date = date('Y-m-d H:i:s');
        
        $content = filter_var(str_replace(array("\n", "\r", "\r\n"), ' ', $text), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
        
        $text = $content;
        
        $dbreferences = DB::table('references')->get();     //reference corpus
        
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
            
        $reportLink = $this->returnDetailedPDFReport($papertitle, $plagiarism_percentage, $word_count, $sentence_count, $character_count, $matching_sentences, $matching_sources, $searchType, $date, $result, $book, $search_mode_description);
        
        $destinationPath = config('app.reportDestinationPath').'/report'.$id.'.pdf';
        
        $uploaded = Storage::put($destinationPath, $reportLink);
        $fullPath = 'http://localhost/3rdeye/resources/app/'.$destinationPath;
            
        DB::table('reports')
            ->where('id', $id)
            ->update(['status' => 'done', 'character_count' => $character_count, 'word_count' => $word_count, 'sentence_count' => $sentence_count, 'matching_sentences' => $matching_sentences, 'matching_sources' => $matching_sources, 'plagiarism_percentage' => $plagiarism_percentage, 'search_result' => $search_result, 'pdf_report' => $fullPath, 'updated_at' => $updated_at]);
        
       
        $percent = round($plagiarism_percentage);
        //send email 
        
        Mail::to(Auth::user()->email)
            ->send(new \App\Mail\MyMail(Auth::user()->name, $papertitle, $percent , $word_count, $sentence_count, $character_count, $matching_sentences, $matching_sources, $searchType, $date, $fullPath));   
            
        
        }
    

     public function returnDetailedPDFReport($papertitle, $plagiarism_percentage, $word_count, $sentence_count, $character_count, $matching_sentences, $matching_sources, $search_type, $upload_time, $search_result_array, $book, $searchmode)
     {
        
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
    
}
