<?php

namespace Sajjadmgd\Zarinpal\Tests\Unit;

use Sajjadmgd\Zarinpal\Tests\TestCase;
use Sajjadmgd\Zarinpal\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate')->run();
    }

    /** @test */
    function a_transaction_has_an_amount()
    {
        $transaction = Transaction::factory()->create(['amount' => 10000]);
        $this->assertEquals(10000, $transaction->amount);
    }
}
