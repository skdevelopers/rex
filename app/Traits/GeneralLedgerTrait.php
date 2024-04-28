<?php

namespace App\Traits;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Sales;
use App\Models\SalesInvoice;
use App\Models\StockTransaction;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Account;
use App\Models\User;
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
     * @param User $userId ID of the user who created the transaction
     * @param null $table Table involved in the transaction
     * @param null $recordId ID of the record involved in the transaction
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

    /**
     * Create accounting entries for product addition.
     *
     * @param Product $product
     * @return void
     * @throws \Exception
     */
    public function createProductEntries(Product $product)
    {
        // Get the user who initiated the transaction (assuming it's stored in session or provided in the request)
        $userId = auth()->id();

        // Start a transaction
        DB::beginTransaction();

        try {
            // Create a new transaction record
            $transaction = Transaction::create([
                'user_id' => $userId,
                'description' => "Added product: {$product->name}",
                'transaction_date' => now(),
            ]);

            // Get the inventory account ID dynamically based on the account name or number
            $inventoryAccountId = $this->getAccountId('Stock-in-Trade'); // Example account name

            // Create transaction detail for inventory increase
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'entryable_id' => $product->id,
                'entryable_type' => Product::class,
                'account_id' => $inventoryAccountId,
                'type' => 'debit', // Increase in inventory
                'amount' => $product->quantity,
            ]);

            // Create stock transaction record
            StockTransaction::create([
                'product_id' => $product->id,
                'warehouse_id' => $product->warehouse_id, // Assuming the product has a warehouse ID
                'transaction_type' => 'purchase', // Assuming it's a purchase
                'quantity' => $product->quantity,
                'unit_price' => $product->unit_price,
                'total_value' => $product->unit_price * $product->quantity,
                'transaction_date' => now(),
            ]);

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollback();
            throw $e;
        }
    }


    /**
     * Record a sale transaction in the 'sales' table.
     *
     * @param Product $product
     * @param  int  $quantity
     * @param  float  $unitPrice
     * @return void
     */
    private function recordSaleTransaction(Product $product, $quantity, $unitPrice)
    {
        $totalAmount = $quantity * $unitPrice;

        // Example: Assuming you have a 'sales' table to record sales transactions
        Sales::create([
            'date' => now(),
            'party_name' => 'Customer Name', // Placeholder for customer name
            'product_id' => $product->id,
            'qty' => $quantity,
            'rate' => $unitPrice,
            'percent' => 0.00, // Placeholder for any percentage calculation
            'amount' => $totalAmount,
            'previous_balance' => 0.00, // Placeholder for previous balance
        ]);
    }

    /**
     * Create sales invoice entries for product addition.
     *
     * @param \App\Models\SalesInvoice $invoice
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
}
