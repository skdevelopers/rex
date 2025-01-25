<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\Account;
use Exception;

trait InventoryTransactionTrait
{
    use GeneralLedgerTrait;

    /**
     * Record inventory transaction (purchase or sale).
     *
     * @param Product $product
     * @param int $quantity
     * @param float $unitPrice
     * @param string $transactionType 'purchase' or 'sale'
     * @param int $userId
     * @return void
     * @throws Exception
     */
    public function recordInventoryTransaction(Product $product, int $quantity, float $unitPrice, string $transactionType, int $userId): void
    {
        $description = ucfirst($transactionType) . " Transaction: {$product->name}";

        $amount = $quantity * $unitPrice;
        $inventoryAccountId = $this->getAccountId('Stock-in-Trade');
        $revenueAccountId = $this->getAccountId('Sales Revenue');

        $details = [];
        if ($transactionType === 'purchase') {
            $details[] = [
                'entryable_id' => $product->id,
                'entryable_type' => Product::class,
                'account_id' => $inventoryAccountId,
                'type' => 'debit',
                'total_amount' => $amount,
            ];
            $details[] = [
                'entryable_id' => $product->id,
                'entryable_type' => Product::class,
                'account_id' => $this->getAccountId('Accounts Payable'),
                'type' => 'credit',
                'total_amount' => $amount,
            ];
            $this->increaseInventory($product, $quantity, $unitPrice);
        } elseif ($transactionType === 'sale') {
            $this->checkStockAvailability($product, $quantity);
            $details[] = [
                'entryable_id' => $product->id,
                'entryable_type' => Product::class,
                'account_id' => $inventoryAccountId,
                'type' => 'credit',
                'total_amount' => $amount,
            ];
            $details[] = [
                'entryable_id' => $product->id,
                'entryable_type' => Product::class,
                'account_id' => $revenueAccountId,
                'type' => 'debit',
                'total_amount' => $amount,
            ];
            $this->decreaseInventory($product, $quantity);
        }

        $this->generateGL($description, $details, 'Transaction Reference', $transactionType, $userId);
    }

    /**
     * Get account ID by name.
     *
     * @param string $accountName
     * @return int
     * @throws Exception
     */
    protected function getAccountId(string $accountName): int
    {
        $account = Account::where('name', $accountName)->first();
        if (!$account) {
            throw new Exception("Account '{$accountName}' not found.");
        }
        return $account->id;
    }
    /**
     * Increase inventory for a product.
     *
     * @param Product $product
     * @param int $quantity
     * @param float $unitPrice
     * @return void
     */
    protected function increaseInventory(Product $product, int $quantity, float $unitPrice): void
    {
        $product->quantity += $quantity;

        // Update the average unit price for the inventory valuation
        $totalCost = ($product->quantity * $product->unit_price) + ($quantity * $unitPrice);
        $totalQuantity = $product->quantity;
        $product->unit_price = $totalCost / $totalQuantity;

        $product->save();
    }

    /**
     * Decrease inventory for a product.
     *
     * @param Product $product
     * @param int $quantity
     * @return void
     * @throws Exception
     */
    protected function decreaseInventory(Product $product, int $quantity): void
    {
        if ($product->quantity < $quantity) {
            throw new Exception("Insufficient stock for product {$product->name}");
        }

        $product->quantity -= $quantity;
        $product->save();
    }
    /**
     * Check if sufficient stock is available for a product.
     *
     * @param Product $product
     * @param int $quantity
     * @return void
     * @throws Exception
     */
    protected function checkStockAvailability(Product $product, int $quantity): void
    {
        if ($product->quantity < $quantity) {
            throw new Exception("Insufficient stock for product '{$product->name}'. Available: {$product->quantity}, Requested: {$quantity}");
        }
    }

}
