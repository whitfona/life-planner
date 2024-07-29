<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $baseBoxUrl = '/api/boxes';

    public function getBaseBoxUrl(): string
    {
        return $this->baseBoxUrl;
    }
}
