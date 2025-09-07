<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountCategory;
use App\Models\AccountCode;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Carbon\Carbon;

class SampleReportSeeder extends Seeder
{
    public function run()
    {
        // === STEP 1: MASTER CATEGORIES ===
        $categories = [
            ['code' => 'kb', 'name' => 'Kas & Bank', 'type' => 'Asset'],
            ['code' => 'piu', 'name' => 'Piutang', 'type' => 'Asset'],
            ['code' => 'inv', 'name' => 'Persediaan', 'type' => 'Asset'],
            ['code' => 'paj', 'name' => 'Pajak Dibayar Dimuka', 'type' => 'Asset'],
            ['code' => 'bpp', 'name' => 'Beban Dibayar Dimuka', 'type' => 'Asset'],
            ['code' => 'equ', 'name' => 'Ekuitas', 'type' => 'Equity'],
            ['code' => 'hpp', 'name' => 'Harga Pokok Penjualan', 'type' => 'Expense'],
            ['code' => 'pen', 'name' => 'Pendapatan', 'type' => 'Revenue'],
        ];

        foreach ($categories as $c) {
            $cat = AccountCategory::firstOrCreate(
                ['category_code' => $c['code']],
                [
                    'category_name' => $c['name'],
                    'category_type' => $c['type'],
                    'is_active' => true
                ]
            );

            // Buat juga AccountCode agar code_id terisi
            AccountCode::firstOrCreate(
                ['code' => strtoupper($c['code'])],
                [
                    'code_name' => $c['name'],
                    'kelkode_id' => $cat->id,
                    'is_active' => true
                ]
            );
        }

        // === STEP 2: CHART OF ACCOUNTS ===
        $accounts = [
            ['num' => '1-001', 'name' => 'Kas Ditangan Jannati Residence II', 'normal' => 'Debit', 'kelkode' => 'kb'],
            ['num' => '1-002', 'name' => 'Kas BRI', 'normal' => 'Debit', 'kelkode' => 'kb'],
            ['num' => '1-003', 'name' => 'Kas Bank Mandiri', 'normal' => 'Debit', 'kelkode' => 'kb'],
            ['num' => '1-004', 'name' => 'Kas BTN Syariah', 'normal' => 'Debit', 'kelkode' => 'kb'],
            ['num' => '1-005', 'name' => 'Piutang Rumah Jannati', 'normal' => 'Debit', 'kelkode' => 'piu'],
            ['num' => '1-006', 'name' => 'Piutang Toko MPJ', 'normal' => 'Debit', 'kelkode' => 'piu'],
            ['num' => '1-007', 'name' => 'Persediaan Tanah', 'normal' => 'Debit', 'kelkode' => 'inv'],
            ['num' => '1-008', 'name' => 'Persediaan Rumah', 'normal' => 'Debit', 'kelkode' => 'inv'],
            ['num' => '1-009', 'name' => 'Uang Muka Pajak', 'normal' => 'Debit', 'kelkode' => 'paj'],
            ['num' => '1-010', 'name' => 'Sewa Dibayar Dimuka', 'normal' => 'Debit', 'kelkode' => 'bpp'],
            ['num' => '3-001', 'name' => 'Modal Disetor', 'normal' => 'Credit', 'kelkode' => 'equ'],
            ['num' => '4-001', 'name' => 'Penjualan Rumah', 'normal' => 'Credit', 'kelkode' => 'pen'],
            ['num' => '5-316', 'name' => 'Beban HPP Rumah', 'normal' => 'Debit', 'kelkode' => 'hpp'],
            // ... bisa extend sampai 50 akun dari PDF
        ];

        foreach ($accounts as $a) {
            $cat = AccountCategory::where('category_code', $a['kelkode'])->first();
            $code = AccountCode::where('code', strtoupper($a['kelkode']))->first();

            ChartOfAccount::firstOrCreate(
                ['account_number' => $a['num']],
                [
                    'account_name' => $a['name'],
                    'account_type' => 'Riil',
                    'kelkode_id' => $cat ? $cat->id : null,
                    'code_id' => $code ? $code->id : null, // ✅ fix error
                    'normal_balance' => $a['normal'],
                    'is_active' => true
                ]
            );
        }

        // === STEP 3: JOURNAL ENTRIES ===
        $journals = [
            ['date' => '2021-01-01', 'no' => '01/K/BB/01/21', 'desc' => 'Setoran Modal Tunai', 'debit' => '1-001', 'credit' => '3-001', 'amount' => 100000000],
            ['date' => '2021-01-02', 'no' => '02/K/BB/01/21', 'desc' => 'Beli Tanah Jannati', 'debit' => '1-007', 'credit' => '1-001', 'amount' => 50000000],
            ['date' => '2021-01-03', 'no' => '03/K/BB/01/21', 'desc' => 'Bayar Sewa Kantor', 'debit' => '1-010', 'credit' => '1-001', 'amount' => 10000000],
            ['date' => '2021-01-04', 'no' => '04/K/BB/01/21', 'desc' => 'Bayar Uang Muka Pajak', 'debit' => '1-009', 'credit' => '1-001', 'amount' => 2500000],
            ['date' => '2021-01-05', 'no' => '05/K/BB/01/21', 'desc' => 'Beli Persediaan Rumah', 'debit' => '1-008', 'credit' => '1-001', 'amount' => 70000000],
            ['date' => '2021-01-06', 'no' => '06/K/BB/01/21', 'desc' => 'Bayar Piutang Toko MPJ', 'debit' => '1-006', 'credit' => '1-001', 'amount' => 5000000],
            ['date' => '2021-01-07', 'no' => '07/K/BB/01/21', 'desc' => 'Bayar Piutang Rumah Jannati', 'debit' => '1-005', 'credit' => '1-001', 'amount' => 8000000],
            ['date' => '2021-01-08', 'no' => '08/K/BB/01/21', 'desc' => 'Deposit BTN', 'debit' => '1-004', 'credit' => '1-001', 'amount' => 15000000],
            ['date' => '2021-01-09', 'no' => '09/K/BB/01/21', 'desc' => 'Deposit Mandiri', 'debit' => '1-003', 'credit' => '1-001', 'amount' => 25000000],
            ['date' => '2021-01-10', 'no' => '10/K/BB/01/21', 'desc' => 'Deposit BRI', 'debit' => '1-002', 'credit' => '1-001', 'amount' => 30000000],
            // ... extend sampai 50 jurnal dari PDF
        ];

        foreach ($journals as $j) {
            $entry = JournalEntry::create([
                'entry_date' => Carbon::parse($j['date']),
                'entry_number' => $j['no'],
                'description' => $j['desc'],
                'reference_number' => $j['no'],
                'total_amount' => $j['amount']
            ]);

            $debitAcc = ChartOfAccount::where('account_number', $j['debit'])->first();
            $creditAcc = ChartOfAccount::where('account_number', $j['credit'])->first();

            if ($debitAcc && $creditAcc) {
                JournalEntryDetail::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $debitAcc->id,
                    'debit_amount' => $j['amount'],
                    'credit_amount' => 0,
                    'description' => $j['desc']
                ]);

                JournalEntryDetail::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $creditAcc->id,
                    'debit_amount' => 0,
                    'credit_amount' => $j['amount'],
                    'description' => $j['desc']
                ]);
            }
        }
    }
}
