<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MethodsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAnalyseDataPerWeek()
    {
        $result=app('App\Http\Controllers\MainController')->analyseDataPerWeek([
            ["user_id" => "3121",
            "created_at" => "2016-07-19",
            "onboarding_perentage" => "40",
            "count_applications" => "0",
            "count_accepted_applications" => "0"]
          ]);
        print_r($result);
        $this->assertTrue(true);
    }

    public function testGetColourPerWeek()
    {
        $result=app('App\Http\Controllers\MainController')->getColourPerWeek([
            ["user_id" => "3121",
            "created_at" => "2016-07-19",
            "onboarding_perentage" => "40",
            "count_applications" => "20",
            "count_accepted_applications" => "10"]
          ]);
        print_r($result);
        $this->assertTrue(true);
    }
    public function testCsvToCollection()
    {
        $_SERVER['DOCUMENT_ROOT']="Update Full Path";
        $result=app('App\Http\Controllers\MainController')->csvToCollection($_SERVER['DOCUMENT_ROOT'].'/export.csv',';');
        print_r($result);
        $this->assertTrue(true);
    }
}
