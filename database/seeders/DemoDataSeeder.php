<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 5; $i++) {
            User::firstOrCreate(
                ['email' => "user$i@demo.com"],
                [
                    'name' => "User $i",
                    'password' => Hash::make('password'),
                    'role' => 'accountant',
                    'is_active' => true
                ]
            );
        }

        $accounts = ChartOfAccount::all();
        if ($accounts->count() < 2) {
            $this->command->warn("Seeder gagal: butuh minimal 2 akun di Chart of Accounts");
            return;
        }

        // Buat 50 jurnal sample
        for ($i = 1; $i <= 50; $i++) {
            $debitAcc  = $accounts->random();
            $creditAcc = $accounts->where('id', '!=', $debitAcc->id)->random();
            $amount    = $faker->numberBetween(100000, 5000000);

            $journal = JournalEntry::create([
                'entry_date'      => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'entry_number'    => 'JN-' . $i . '-' . time(),
                'description'     => $faker->sentence(3),
                'reference_number' => 'REF-' . $faker->unique()->numerify('###'),
                'total_amount'    => $amount
            ]);

            JournalEntryDetail::create([
                'journal_entry_id' => $journal->id,
                'account_id'       => $debitAcc->id,
                'debit_amount'     => $amount,
                'credit_amount'    => 0,
                'description'      => "Debit ke {$debitAcc->account_name}"
            ]);

            JournalEntryDetail::create([
                'journal_entry_id' => $journal->id,
                'account_id'       => $creditAcc->id,
                'debit_amount'     => 0,
                'credit_amount'    => $amount,
                'description'      => "Kredit ke {$creditAcc->account_name}"
            ]);
        }
    }
}
