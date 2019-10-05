<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class MainController extends Controller
{
    // Programe starting point
    public function index() {
        // Variables
        $result_array=[];
        $colour_array=[];
        $start_date="";
        // Get input as a collection
        // Need change here if need to import data from database (convert to cllection)
        $input_collection=$this->csvToCollection($_SERVER['DOCUMENT_ROOT'].'/export.csv',';');
        //Loop through collection 
        foreach($input_collection as $input){

            // Checking weeks
            if(Carbon::parse($input['created_at'])->startOfWeek() != $start_date){
                // Get data per week
                $data_per_week=$input_collection->whereBetween( 'created_at', [Carbon::parse($input['created_at'])->startOfWeek(), Carbon::parse($input['created_at'])->endOfWeek()] )->all();
                // Update variable
                $start_date=Carbon::parse($input['created_at'])->startOfWeek();
                // Calling week data to analyse and return data as an array(Line per week)
                $result_array[Carbon::parse($input['created_at'])->startOfWeek()->toDateString().' to '.Carbon::parse($input['created_at'])->endOfWeek()->toDateString()]=$this->analyseDataPerWeek($data_per_week);
            }
        }

        // Get colour values to seperate array
        foreach ($result_array as $value) {
           $colour_array[]=$value['colour'];
        }

        // Update colour with percentage to highest value
        foreach ($result_array as $key => $value) {
            $result_array[$key]['colour']=number_format((float)($value['colour']/max($colour_array))*120, 2, '.', '');
        }

        // Return final output 
        return response()->json($result_array, 200);
    }

    // Analyse data per week and return array of data
    public function analyseDataPerWeek($data_per_week){
        // Variables
        $final_result=[];
        $coodinates_x_y=[];

        // Loop through percentage array
        for($i = 0; $i <= 100; ++$i) {
            $count=0;
            // Loop through data array
            foreach($data_per_week as $data){
                if($i <= $data['onboarding_perentage']){
                    $count++;
                }
            }
            $coodinates_x_y[$i]=round(($count/count($data_per_week))*100,2);
        }
        // Calling color method
        $color=$this->getColourPerWeek($data_per_week);

        // Filling final array with data
        $final_result['colour']=$color;
        $final_result['coodinates']=$coodinates_x_y;
        return $final_result;
    }

    // Getting color of line according to the accept perceatage of workflow applicatins
    public function getColourPerWeek($data_per_week){
        // Variables
        $accept=0;
        $request=0;
        // Loop through data array
        foreach($data_per_week as $data){
            $accept=$accept+$data['count_accepted_applications'];
            $request=$request+$data['count_applications'];
        }

        // Check for devision by zero
        if($request != 0){
            return number_format((float)($accept/$request)*120, 2, '.', '');
        }else{
            return 0;
        } 
    }
  
    // Convert CSV to JSON object
    function csvToCollection($file,$delimiter)
    {   
        // Open csv file to get data
        if (($handle = fopen($file, "r")) === false)
        {
                die("can't open the file.");
        }
        // Getting headers of objects
        $csv_headers = fgetcsv($handle, 4000, $delimiter);
        $csv = array();
        // Loop through all objects
        while ($row = fgetcsv($handle, 4000, $delimiter))
        {
                $csv[] = array_combine($csv_headers, $row);
        }
        // Closing file
        fclose($handle);
        // Return result as json 
        return collect($csv);
    }
}


