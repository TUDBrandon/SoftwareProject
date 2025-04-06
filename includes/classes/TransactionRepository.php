<?php
/**
 * TransactionRepository class
 * 
 * Handles database operations for transactions
 */
class TransactionRepository {
    private $connection;
    
    /**
     * Constructor
     * 
     * @param PDO $connection Database connection
     */
    public function __construct($connection = null) {
        if ($connection) {
            $this->connection = $connection;
        } else {
            // Try to establish connection if not provided
            try {
                require_once __DIR__ . '/../../src/DBconnect.php';
                global $connection;
                $this->connection = $connection;
            } catch (Exception $e) {
                error_log("Error connecting to database: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Get all transactions
     * 
     * @return array Array of Transaction objects
     */
    public function getAllTransactions() {
        $transactions = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM transactions";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $transactions[] = $this->createTransactionFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving transactions: " . $e->getMessage());
        }
        
        return $transactions;
    }
    
    /**
     * Get transaction by ID
     * 
     * @param int $id Transaction ID
     * @return Transaction|null Transaction object or null if not found
     */
    public function getTransactionById($id) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM transactions WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $this->createTransactionFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving transaction by ID: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Get transactions by user ID
     * 
     * @param int $userId User ID
     * @return array Array of Transaction objects
     */
    public function getTransactionsByUserId($userId) {
        $transactions = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM transactions WHERE userId = :userId";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $transactions[] = $this->createTransactionFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving transactions by user ID: " . $e->getMessage());
        }
        
        return $transactions;
    }
    
    /**
     * Get transactions by status
     * 
     * @param string $status Transaction status
     * @return array Array of Transaction objects
     */
    public function getTransactionsByStatus($status) {
        $transactions = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM transactions WHERE status = :status";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':status', $status);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $transactions[] = $this->createTransactionFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving transactions by status: " . $e->getMessage());
        }
        
        return $transactions;
    }
    
    /**
     * Save transaction to database
     * 
     * @param Transaction $transaction Transaction object
     * @return bool True on success, false on failure
     */
    public function saveTransaction(Transaction $transaction) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            // Determine if this is an insert or update
            if ($transaction->getId()) {
                // Update existing transaction
                $sql = "UPDATE transactions SET 
                        userId = :userId,
                        subtotal = :subtotal,
                        tax = :tax,
                        total = :total,
                        status = :status,
                        updatedAt = NOW(),
                        paymentId = :paymentId
                        WHERE id = :id";
                $statement = $this->connection->prepare($sql);
                $statement->bindParam(':id', $transaction->getId(), PDO::PARAM_INT);
            } else {
                // Insert new transaction
                $sql = "INSERT INTO transactions 
                        (userId, subtotal, tax, total, status, createdAt, updatedAt, paymentId) 
                        VALUES 
                        (:userId, :subtotal, :tax, :total, :status, NOW(), NOW(), :paymentId)";
                $statement = $this->connection->prepare($sql);
            }
            
            // Bind common parameters
            $userId = $transaction->getUserId();
            $subtotal = $transaction->getSubtotal();
            $tax = $transaction->getTax();
            $total = $transaction->getTotal();
            $status = $transaction->getStatus();
            $paymentId = $transaction->getPaymentId();
            
            $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
            $statement->bindParam(':subtotal', $subtotal);
            $statement->bindParam(':tax', $tax);
            $statement->bindParam(':total', $total);
            $statement->bindParam(':status', $status);
            $statement->bindParam(':paymentId', $paymentId);
            
            $success = $statement->execute();
            
            // If this was an insert, get the new ID and set it on the transaction
            if ($success && !$transaction->getId()) {
                $newId = $this->connection->lastInsertId();
                $transaction->setId($newId);
                
                // Now save the transaction items
                $this->saveTransactionItems($transaction);
            }
            
            return $success;
        } catch (Exception $e) {
            error_log("Error saving transaction: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Save transaction items to database
     * 
     * @param Transaction $transaction Transaction object with items
     * @return bool True on success, false on failure
     */
    private function saveTransactionItems(Transaction $transaction) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $transactionId = $transaction->getId();
            $items = $transaction->getItems();
            
            // First delete any existing items for this transaction
            $deleteSql = "DELETE FROM transaction_items WHERE transactionId = :transactionId";
            $deleteStatement = $this->connection->prepare($deleteSql);
            $deleteStatement->bindParam(':transactionId', $transactionId, PDO::PARAM_INT);
            $deleteStatement->execute();
            
            // Now insert all items
            $insertSql = "INSERT INTO transaction_items 
                        (transactionId, productId, quantity, price) 
                        VALUES 
                        (:transactionId, :productId, :quantity, :price)";
            
            foreach ($items as $item) {
                $statement = $this->connection->prepare($insertSql);
                $statement->bindParam(':transactionId', $transactionId, PDO::PARAM_INT);
                $statement->bindParam(':productId', $item['productId'], PDO::PARAM_INT);
                $statement->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                $statement->bindParam(':price', $item['price']);
                $statement->execute();
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error saving transaction items: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update transaction status
     * 
     * @param int $id Transaction ID
     * @param string $status New status
     * @return bool True on success, false on failure
     */
    public function updateTransactionStatus($id, $status) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "UPDATE transactions SET status = :status, updatedAt = NOW() WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':status', $status);
            
            return $statement->execute();
        } catch (Exception $e) {
            error_log("Error updating transaction status: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete transaction
     * 
     * @param int $id Transaction ID
     * @return bool True on success, false on failure
     */
    public function deleteTransaction($id) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            // First delete related transaction items
            $deleteItemsSql = "DELETE FROM transaction_items WHERE transactionId = :id";
            $deleteItemsStatement = $this->connection->prepare($deleteItemsSql);
            $deleteItemsStatement->bindParam(':id', $id, PDO::PARAM_INT);
            $deleteItemsStatement->execute();
            
            // Then delete the transaction
            $deleteTransactionSql = "DELETE FROM transactions WHERE id = :id";
            $deleteTransactionStatement = $this->connection->prepare($deleteTransactionSql);
            $deleteTransactionStatement->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $deleteTransactionStatement->execute();
        } catch (Exception $e) {
            error_log("Error deleting transaction: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create Transaction object from database data
     * 
     * @param array $data Transaction data from database
     * @return Transaction Transaction object
     */
    private function createTransactionFromData($data) {
        $transaction = new Transaction();
        
        // Set basic transaction properties
        $transaction->setId($data['id']);
        $transaction->setUserId($data['userId']);
        $transaction->setSubtotal($data['subtotal']);
        $transaction->setTax($data['tax']);
        $transaction->setTotal($data['total']);
        $transaction->setStatus($data['status']);
        $transaction->setCreatedAt($data['createdAt']);
        $transaction->setUpdatedAt($data['updatedAt']);
        $transaction->setPaymentId($data['paymentId']);
        
        // Load transaction items
        $this->loadTransactionItems($transaction);
        
        return $transaction;
    }
    
    /**
     * Load transaction items for a transaction
     * 
     * @param Transaction $transaction Transaction object to load items for
     */
    private function loadTransactionItems(Transaction $transaction) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $transactionId = $transaction->getId();
            
            $sql = "SELECT ti.*, p.name, p.image 
                    FROM transaction_items ti
                    JOIN products p ON ti.productId = p.id
                    WHERE ti.transactionId = :transactionId";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':transactionId', $transactionId, PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $item) {
                $transaction->addItem([
                    'productId' => $item['productId'],
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'image' => $item['image']
                ]);
            }
        } catch (Exception $e) {
            error_log("Error loading transaction items: " . $e->getMessage());
        }
    }
}
