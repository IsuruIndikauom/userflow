<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APITest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAPI()
    {
        $_SERVER['DOCUMENT_ROOT']="Update Full Path";
        $response = $this->json('GET', '/api/data');
        $response->assertStatus(200);
    }
}
