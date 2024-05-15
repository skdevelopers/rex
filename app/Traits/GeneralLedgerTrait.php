<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Exception;
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
     * @param User $user User who initiated the transaction
     * @return void
     * @throws Exception
     */
    public function generateGL($description, $details, $reference, $type, User $user)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create a new transaction
            $transaction = Transaction::create([
                'description' => $description,
                'reference' => $reference,
                'type' => $type,
                'user_id' => $user->id,
                'transaction_date' => now()->toDateString(),
            ]);

            // Create transaction details
            foreach ($details as $detail) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'entryable_id' => $detail['entryable_id'],
                    'entryable_type' => $detail['entryable_type'],
                    'account_id' => $detail['account_id'],
                    'type' => $detail['type'], // 'debit' or 'credit'
                    'total_amount' => $detail['total_amount'],
                ]);
            }

            // Commit the database transaction
            DB::commit();
        } catch (Exception $e) {
            // Rollback the transaction on error
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Record a sale transaction.
     *
     * @param Product $product
     * @param int $quantity
     * @param float $unitPrice
     * @param User $user
     * @return void
     * @throws Exception
     */
    public function recordSaleTransaction(Product $product, $quantity, $unitPrice, User $user)
    {
        $totalAmount = $quantity * $unitPrice;

        // Example: Create a sale record
        Sale::create([
            'date' => now()->toDateString(),
            'party_name' => 'Customer Name', // Placeholder for customer name
            'product_id' => $product->id,
            'qty' => $quantity,
            'rate' => $unitPrice,
            'percent' => 0.00, // Placeholder for any percentage calculation
            'amount' => $totalAmount,
            'previous_balance' => 0.00, // Placeholder for previous balance
        ]);

        // Define transaction details
        $details = [
            [
                'entryable_id' => $product->id,
                'entryable_type' => Product::class,
                'account_id' => $product->inventory_account_id, // Example: Inventory account ID
                'type' => 'credit', // Decrease in inventory
                'total_amount' => $product->unit_price * $product->quantity, // Monetary value of the inventory increase
            ],
            // Add more transaction details as needed
        ];

        // Generate general ledger entries for the sale transaction
        $this->generateGL('Sale Transaction', $details, 'Sale Reference', 'sale', $user);
    }
}
