<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $baseBoxUrl = '/api/boxes';
    protected $baseItemUrl = '/api/items';

    public function getBaseBoxUrl(): string
    {
        return $this->baseBoxUrl;
    }

    public function getBaseItemUrl(): string
    {
        return $this->baseItemUrl;
    }
}
