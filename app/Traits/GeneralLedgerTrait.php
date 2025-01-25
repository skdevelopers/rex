<?php

namespace App\Traits;

use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Illuminate\Support\Facades\DB;
use Exception;

trait GeneralLedgerTrait
{
    /**
     * Generate general ledger entries dynamically.
     *
     * @param string $description Description of the transaction
     * @param array $details Array of transaction details
     * @param string $reference Reference for the transaction
     * @param string $type Type of transaction (e.g., 'sale', 'purchase')
     * @param int $userId ID of the user who initiated the transaction
     * @return void
     * @throws Exception
     */
    public function generateGL(string $description, array $details, string $reference, string $type, int $userId)
    {
        DB::beginTransaction();

        try {
            $journalEntry = JournalEntry::create([
                'description' => $description,
                'reference' => $reference,
                'type' => $type,
                'user_id' => $userId,
                'transaction_date' => now(),
            ]);

            $journalDetails = [];
            foreach ($details as $detail) {
                $journalDetails[] = [
                    'journal_entry_id' => $journalEntry->id,
                    'entryable_id' => $detail['entryable_id'],
                    'entryable_type' => $detail['entryable_type'],
                    'account_id' => $detail['account_id'],
                    'type' => $detail['type'], // 'debit' or 'credit'
                    'total_amount' => $detail['total_amount'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            JournalEntryDetail::insert($journalDetails);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}

