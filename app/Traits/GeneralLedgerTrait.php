<?php

namespace App\Traits;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\SalesInvoice;
use App\Models\StockTransaction;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

trait GeneralLedgerTrait
{
    /**
     * Generate general ledger entries for a transaction.
     *
     * @param string $description Description of the transaction
     * @param array $details Array of transaction details
     * @param string $reference Reference for the transaction
     * @param string $type Type of transaction (e.g., 'sale', 'purchase')
     * @param int $userId ID of the user who created the transaction
     * @param string|null $table Table involved in the transaction
     * @param int|null $recordId ID of the record involved in the transaction
     * @return void
     */
    public function generateGL($description, $details, $reference, $type, User $userId, $table = null, $recordId = null)
    {
        // Create a new transaction
        $transaction = Transaction::create([
            'description' => $description,
            'reference' => $reference,
            'type' => $type,
            'user_id' => $userId, // Store the user ID
            'table' => $table,
            'record_id' => $recordId,
        ]);

        // Create transaction details
        foreach ($details as $detail) {
            $transaction->transactionDetails()->create([
                'entryable_id' => $detail['entryable_id'],
                'entryable_type' => $detail['entryable_type'],
                'account_id' => $detail['account_id'],
                'type' => $detail['type'],
                'amount' => $detail['amount'],
            ]);
        }

        // Other custom logic for generating entries
    }


    private function getAccountId($accountNameOrNumber)
    {
        // Retrieve the account ID based on the account name or number
        $account = Account::where('name', $accountNameOrNumber)
            ->orWhere('account_number', $accountNameOrNumber)
            ->first();

        if ($account) {
            return $account->id;
        }

        // If account not found, handle the scenario (e.g., throw an exception, use a default account, etc.)
        // For now, let's return null
        return null;
    }
    /**
     * Create sales invoice entries for product addition.
     *
     * @param  \App\Models\SalesInvoice  $invoice
     * @return void
     * @throws \Exception
     */
    public function createSalesInvoiceEntries(SalesInvoice $invoice)
    {
        // Get the user who initiated the transaction (assuming it's stored in session or provided in the request)
        $userId = auth()->id();

        // Start a transaction
        DB::beginTransaction();

        try {
            // Create a new transaction record for sales
            $transaction = Transaction::create([
                'user_id' => $userId,
                'description' => "Sales Invoice: #{$invoice->id}",
                'transaction_date' => $invoice->invoice_date,
            ]);

            // Iterate over each item in the invoice
            foreach ($invoice->items as $item) {
                // Get the inventory record for the product
                $inventory = Inventory::where('product_id', $item->product_id)
                    ->where('warehouse_id', $invoice->warehouse_id)
                    ->first();

                // Create transaction detail for inventory decrease
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'entryable_id' => $item->product_id,
                    'entryable_type' => Product::class,
                    'account_id' => $inventory->account_id, // Assuming each inventory has an associated account
                    'type' => 'credit', // Decrease in inventory
                    'total_amount' => $item->quantity,
                ]);

                // Update the inventory quantity
                $inventory->update(['quantity' => $inventory->quantity - $item->quantity]);
            }

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollback();
            throw $e;
        }
    }

}
