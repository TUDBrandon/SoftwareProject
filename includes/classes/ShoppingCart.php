<?php
/**
 * ShoppingCart class
 * 
 * Represents a shopping cart in the TechTrade system
 * Associated with a User and generates Transactions
 */
class ShoppingCart {
    // Private properties - encapsulation
    private $id;
    private $userId;
    private $items = [];
    private $createdAt;
    private $updatedAt;
    private $subtotal = 0;
    private $tax = 0;
    private $total = 0;
    
    /**
     * Constructor
     * 
     * @param array $data ShoppingCart data
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? $data['cart_id'] ?? null;
        $this->userId = $data['user_id'] ?? $data['userId'] ?? null;
        $this->createdAt = $data['created_at'] ?? $data['createdAt'] ?? date('Y-m-d H:i:s');
        $this->updatedAt = $data['updated_at'] ?? $data['updatedAt'] ?? date('Y-m-d H:i:s');
        
        // Initialize items if provided
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $this->addItem($item);
            }
        }
    }
    
    /**
     * Get cart ID
     * 
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Get user ID
     * 
     * @return int|null
     */
    public function getUserId() {
        return $this->userId;
    }
    
    /**
     * Get cart items
     * 
     * @return array
     */
    public function getItems() {
        return $this->items;
    }
    
    /**
     * Get cart created date
     * 
     * @return string
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }
    
    /**
     * Get cart updated date
     * 
     * @return string
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }
    
    /**
     * Get cart subtotal
     * 
     * @return float
     */
    public function getSubtotal() {
        return $this->subtotal;
    }
    
    /**
     * Get cart tax
     * 
     * @return float
     */
    public function getTax() {
        return $this->tax;
    }
    
    /**
     * Get cart total
     * 
     * @return float
     */
    public function getTotal() {
        return $this->total;
    }
    
    /**
     * Add item to cart
     * 
     * @param array|Product $item Product or item data
     * @param int $quantity Quantity (only used if $item is a Product)
     * @return self
     */
    public function addItem($item, int $quantity = 1) {
        if ($item instanceof Product) {
            // Convert Product to cart item array
            $itemData = [
                'product_id' => $item->getId(),
                'name' => $item->getName(),
                'price' => $item->getPrice(),
                'quantity' => $quantity,
                'image' => $item->getImage()
            ];
        } else {
            // Use provided item data
            $itemData = $item;
            // Ensure quantity is set
            if (!isset($itemData['quantity'])) {
                $itemData['quantity'] = 1;
            }
        }
        
        // Check if product already exists in cart
        $productId = $itemData['product_id'];
        $existingIndex = $this->findItemIndex($productId);
        
        if ($existingIndex !== false) {
            // Update existing item quantity
            $this->items[$existingIndex]['quantity'] += $itemData['quantity'];
        } else {
            // Add new item
            $this->items[] = $itemData;
        }
        
        // Update cart
        $this->updateCart();
        
        return $this;
    }
    
    /**
     * Update item quantity
     * 
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function updateQuantity(int $productId, int $quantity) {
        $index = $this->findItemIndex($productId);
        
        if ($index === false) {
            return false;
        }
        
        if ($quantity <= 0) {
            // Remove item if quantity is 0 or negative
            return $this->removeItem($productId);
        }
        
        // Update quantity
        $this->items[$index]['quantity'] = $quantity;
        
        // Update cart
        $this->updateCart();
        
        return true;
    }
    
    /**
     * Remove item from cart
     * 
     * @param int $productId
     * @return bool
     */
    public function removeItem(int $productId) {
        $index = $this->findItemIndex($productId);
        
        if ($index === false) {
            return false;
        }
        
        // Remove item
        array_splice($this->items, $index, 1);
        
        // Update cart
        $this->updateCart();
        
        return true;
    }
    
    /**
     * Clear cart
     * 
     * @return self
     */
    public function clear() {
        $this->items = [];
        $this->updateCart();
        return $this;
    }
    
    /**
     * Get item count
     * 
     * @return int
     */
    public function getItemCount() {
        return count($this->items);
    }
    
    /**
     * Get total quantity of all items
     * 
     * @return int
     */
    public function getTotalQuantity() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['quantity'];
        }
        return $total;
    }
    
    /**
     * Find item index by product ID
     * 
     * @param int $productId
     * @return int|false
     */
    private function findItemIndex(int $productId) {
        foreach ($this->items as $index => $item) {
            if ($item['product_id'] == $productId) {
                return $index;
            }
        }
        return false;
    }
    
    /**
     * Update cart totals
     */
    private function updateCart() {
        // Calculate subtotal
        $this->subtotal = 0;
        foreach ($this->items as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }
        
        // Calculate tax (assuming 23% VAT)
        $this->tax = $this->subtotal * 0.23;
        
        // Calculate total
        $this->total = $this->subtotal + $this->tax;
        
        // Update timestamp
        $this->updatedAt = date('Y-m-d H:i:s');
    }
    
    /**
     * Generate a transaction from this cart
     * 
     * @return Transaction
     */
    public function generateTransaction() {
        return new Transaction([
            'user_id' => $this->userId,
            'items' => $this->items,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total
        ]);
    }
    
    /**
     * Convert to array
     * 
     * @return array
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'items' => $this->items,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total
        ];
    }
}
