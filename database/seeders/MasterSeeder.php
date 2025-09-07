<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\AccountCategory;
use App\Models\AccountCode;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // ---- ROLES ----
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $accRole   = Role::firstOrCreate(['name' => 'accountant']);
        $viewRole  = Role::firstOrCreate(['name' => 'viewer']);

        // ---- USER ADMIN ----
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

        // ---- ACCOUNT CATEGORIES ----
        $categories = [
            ['ksk', 'Kas & Setara Kas', 'Asset'],
            ['pu', 'Piutang Usaha', 'Asset'],
            ['ps', 'Persediaan', 'Asset'],
            ['kb', 'Kewajiban Bank', 'Liability'],
            ['ek', 'Ekuitas', 'Equity'],
            ['pd', 'Pendapatan', 'Revenue'],
            ['bb', 'Beban-Beban', 'Expense'],
        ];

        foreach ($categories as $c) {
            AccountCategory::firstOrCreate(
                ['category_code' => $c[0]],
                ['category_name' => $c[1], 'category_type' => $c[2], 'is_active' => true]
            );
        }

        // ---- ACCOUNT CODES ----
        $kasCat = AccountCategory::where('category_code', 'ksk')->first();
        $pendapatanCat = AccountCategory::where('category_code', 'pd')->first();

        $codes = [
            ['kb1', $kasCat->id, 'Kas Besar'],
            ['kb2', $kasCat->id, 'Kas Kecil'],
            ['pd1', $pendapatanCat->id, 'Pendapatan Penjualan'],
        ];

        foreach ($codes as $c) {
            AccountCode::firstOrCreate(
                ['code' => $c[0]],
                ['kelkode_id' => $c[1], 'code_name' => $c[2], 'is_active' => true]
            );
        }

        // ---- CHART OF ACCOUNTS ----
        $kasBesarCode = AccountCode::where('code', 'kb1')->first();
        $kasKecilCode = AccountCode::where('code', 'kb2')->first();
        $pendapatanCode = AccountCode::where('code', 'pd1')->first();

        $coa = [
            ['1-001', 'Kas Besar', 'Debit', $kasBesarCode->id, $kasCat->id, 'Debit'],
            ['1-002', 'Kas Kecil', 'Debit', $kasKecilCode->id, $kasCat->id, 'Debit'],
            ['4-001', 'Pendapatan Penjualan', 'Riil', $pendapatanCode->id, $pendapatanCat->id, 'Credit'],
        ];

        foreach ($coa as $a) {
            ChartOfAccount::firstOrCreate(
                ['account_number' => $a[0]],
                [
                    'account_name' => $a[1],
                    'account_type' => $a[2],
                    'code_id' => $a[3],
                    'kelkode_id' => $a[4],
                    'normal_balance' => $a[5],
                    'is_active' => true
                ]
            );
        }

        // ---- SAMPLE JOURNAL ENTRY ----
        $kasBesar = ChartOfAccount::where('account_number', '1-001')->first();
        $pendapatan = ChartOfAccount::where('account_number', '4-001')->first();

        $journal = JournalEntry::create([
            'entry_date' => now()->toDateString(),
            'entry_number' => 'JN-' . now()->format('Ymd-His'),
            'description' => 'Penjualan Rumah Tunai',
            'reference_number' => 'INV-001',
            'total_amount' => 100000000
        ]);

        JournalEntryDetail::create([
            'journal_entry_id' => $journal->id,
            'account_id' => $kasBesar->id,
            'debit_amount' => 100000000,
            'credit_amount' => 0,
            'description' => 'Kas diterima'
        ]);

        JournalEntryDetail::create([
            'journal_entry_id' => $journal->id,
            'account_id' => $pendapatan->id,
            'debit_amount' => 0,
            'credit_amount' => 100000000,
            'description' => 'Pendapatan penjualan rumah'
        ]);
    }
}
