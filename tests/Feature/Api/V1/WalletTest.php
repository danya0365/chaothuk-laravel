<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use Database\Seeders\GeographySeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\ProvinceSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\WorkTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(GeographySeeder::class);
        $this->seed(ProvinceSeeder::class);
        $this->seed(WorkTypeSeeder::class);
    }

    public function test_the_wallet_reads_require_authentication(): void
    {
        $this->getJson('/api/v1/wallet')->assertUnauthorized();
        $this->getJson('/api/v1/wallet/transactions')->assertUnauthorized();
    }

    public function test_a_user_without_a_wallet_still_reads_zero(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/wallet')
            ->assertOk()
            ->assertJsonPath('data.balance', 0)
            ->assertJsonPath('data.status', 'active');
    }

    public function test_the_balance_and_ledger_come_from_the_callers_wallet(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $wallet = UserWallet::create([
            'user_id' => $owner->id,
            'balance' => 1234.5,
            'status' => 'active',
        ]);

        WalletTransaction::create([
            'user_wallet_id' => $wallet->id,
            'type' => 'deposit',
            'amount' => 1500,
            'balance_after' => 1500,
            'description' => 'Test Deposit (Bypass Payment Gateway)',
        ]);
        WalletTransaction::create([
            'user_wallet_id' => $wallet->id,
            'type' => 'payment',
            'amount' => -265.5,
            'balance_after' => 1234.5,
            'description' => 'โปรโมทประกาศงาน',
        ]);

        // A stranger's ledger never bleeds in.
        $other = UserWallet::create([
            'user_id' => $stranger->id,
            'balance' => 99,
        ]);
        WalletTransaction::create([
            'user_wallet_id' => $other->id,
            'type' => 'deposit',
            'amount' => 99,
            'balance_after' => 99,
        ]);

        $this->actingAs($owner, 'sanctum')
            ->getJson('/api/v1/wallet')
            ->assertOk()
            ->assertJsonPath('data.balance', 1234.5);

        $ledger = $this->actingAs($owner, 'sanctum')
            ->getJson('/api/v1/wallet/transactions')
            ->assertOk();

        // Newest first: the payment was recorded last, so it leads the list.
        $this->assertSame(-265.5, $ledger->json('data.0.amount'));
        $this->assertSame(1234.5, $ledger->json('data.0.balance_after'));
        $this->assertSame('payment', $ledger->json('data.0.type'));
        // Whole numbers encode as JSON ints — 1500, not 1500.0.
        $this->assertSame(1500, $ledger->json('data.1.amount'));

        // Only the caller's own two rows, newest first.
        $this->assertCount(2, $ledger->json('data'));
    }

    public function test_transactions_paginate(): void
    {
        $user = User::factory()->create();
        $wallet = UserWallet::create(['user_id' => $user->id, 'balance' => 0]);

        foreach (range(1, 20) as $i) {
            WalletTransaction::create([
                'user_wallet_id' => $wallet->id,
                'type' => 'adjustment',
                'amount' => $i,
                'balance_after' => $i,
            ]);
        }

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/wallet/transactions?per_page=5')
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.total', 20);
    }
}
