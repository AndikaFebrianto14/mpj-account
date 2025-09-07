<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AccountCategory;
use App\Models\AccountCode;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class FullDemoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // ==== USERS ====
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true
            ]
        );
        $admin->assignRole($adminRole);

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

        // ==== ACCOUNT CATEGORIES ====
        $categoryTypes = ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense'];
        for ($i = 1; $i <= 10; $i++) {
            AccountCategory::create([
                'category_code' => 'CAT' . $i,
                'category_name' => ucfirst($faker->word()),
                'category_type' => $faker->randomElement($categoryTypes),
                'is_active' => true
            ]);
        }

        $categories = AccountCategory::all();

        // ==== ACCOUNT CODES ====
        for ($i = 1; $i <= 20; $i++) {
            AccountCode::create([
                'code' => 'CD' . $i,
                'kelkode_id' => $categories->random()->id,
                'code_name' => ucfirst($faker->word()),
                'is_active' => true
            ]);
        }

        $codes = AccountCode::all();

        // ==== CHART OF ACCOUNTS ====
        for ($i = 1; $i <= 50; $i++) {
            $cat = $categories->random();
            $code = $codes->random();

            ChartOfAccount::create([
                'account_number' => str_pad($i, 4, '0', STR_PAD_LEFT),
                'account_name' => ucfirst($faker->words(2, true)),
                'account_type' => $faker->randomElement(['Riil', 'Debit']),
                'code_id' => $code->id,
                'kelkode_id' => $cat->id,
                'normal_balance' => ($cat->category_type == 'Asset' || $cat->category_type == 'Expense') ? 'Debit' : 'Credit',
                'is_active' => true
            ]);
        }

        $accounts = ChartOfAccount::all();

        // ==== JOURNAL ENTRIES ====
        for ($i = 1; $i <= 50; $i++) {
            $debitAcc  = $accounts->random();
            $creditAcc = $accounts->where('id', '!=', $debitAcc->id)->random();
            $amount    = $faker->numberBetween(50000, 5000000);

            $journal = JournalEntry::create([
                'entry_date'       => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
                'entry_number'     => 'JN-' . $i . '-' . time(),
                'description'      => $faker->sentence(4),
                'reference_number' => 'REF-' . $faker->unique()->numerify('###'),
                'total_amount'     => $amount
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
