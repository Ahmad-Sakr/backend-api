<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp() : void
    {
        parent::setUp();
        // set your headers here
        $this->withHeaders([
            'Accept' => 'application/json'
        ]);
    }
}
