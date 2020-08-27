<?php

namespace Tests\Unit;

use Egulias\EmailValidator\Exception\ExpectingAT;
use Tests\TestCase;
use App\Inspections\Spam;

class SpamTest extends TestCase
{

    public function test_it_check_for_invalid_keywords()
    {
        $this->withoutExceptionHandling();

        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->expectException(\Exception::class);

        $spam->detect('Yahoo Customer Support');
    }

    public function test_it_checks_for_key_being_held_down()
    {
        $this->withoutExceptionHandling();

        $spam = new Spam;

        $this->expectException(\Exception::class);

        $spam->detect('Hello world aaaaaaaaaa');

    }
}
