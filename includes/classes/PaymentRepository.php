<?php
/**
 * PaymentRepository class
 * 
 * Handles database operations for payments
 */
class PaymentRepository {
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
     * Get all payments
     * 
     * @return array Array of Payment objects
     */
    public function getAllPayments() {
        $payments = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM payments";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $payments[] = $this->createPaymentFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving payments: " . $e->getMessage());
        }
        
        return $payments;
    }
    
    /**
     * Get payment by ID
     * 
     * @param int $id Payment ID
     * @return Payment|null Payment object or null if not found
     */
    public function getPaymentById($id) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM payments WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $this->createPaymentFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving payment by ID: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Get payments by user ID
     * 
     * @param int $userId User ID
     * @return array Array of Payment objects
     */
    public function getPaymentsByUserId($userId) {
        $payments = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM payments WHERE userId = :userId";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $payments[] = $this->createPaymentFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving payments by user ID: " . $e->getMessage());
        }
        
        return $payments;
    }
    
    /**
     * Get payments by transaction ID
     * 
     * @param int $transactionId Transaction ID
     * @return array Array of Payment objects
     */
    public function getPaymentsByTransactionId($transactionId) {
        $payments = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM payments WHERE transactionId = :transactionId";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':transactionId', $transactionId, PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $payments[] = $this->createPaymentFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving payments by transaction ID: " . $e->getMessage());
        }
        
        return $payments;
    }
    
    /**
     * Get payments by status
     * 
     * @param string $status Payment status
     * @return array Array of Payment objects
     */
    public function getPaymentsByStatus($status) {
        $payments = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM payments WHERE status = :status";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':status', $status);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $payments[] = $this->createPaymentFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving payments by status: " . $e->getMessage());
        }
        
        return $payments;
    }
    
    /**
     * Save payment to database
     * 
     * @param Payment $payment Payment object
     * @return bool True on success, false on failure
     */
    public function savePayment(Payment $payment) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            // Determine if this is an insert or update
            if ($payment->getId()) {
                // Update existing payment
                $sql = "UPDATE payments SET 
                        userId = :userId,
                        transactionId = :transactionId,
                        amount = :amount,
                        method = :method,
                        status = :status,
                        paymentDate = :paymentDate,
                        cardLast4 = :cardLast4,
                        referenceNumber = :referenceNumber
                        WHERE id = :id";
                $statement = $this->connection->prepare($sql);
                $statement->bindParam(':id', $payment->getId(), PDO::PARAM_INT);
            } else {
                // Insert new payment
                $sql = "INSERT INTO payments 
                        (userId, transactionId, amount, method, status, paymentDate, cardLast4, referenceNumber) 
                        VALUES 
                        (:userId, :transactionId, :amount, :method, :status, :paymentDate, :cardLast4, :referenceNumber)";
                $statement = $this->connection->prepare($sql);
            }
            
            // Bind common parameters
            $userId = $payment->getUserId();
            $transactionId = $payment->getTransactionId();
            $amount = $payment->getAmount();
            $method = $payment->getMethod();
            $status = $payment->getStatus();
            $paymentDate = $payment->getPaymentDate();
            $cardLast4 = $payment->getCardLast4();
            $referenceNumber = $payment->getReferenceNumber();
            
            $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
            $statement->bindParam(':transactionId', $transactionId, PDO::PARAM_INT);
            $statement->bindParam(':amount', $amount);
            $statement->bindParam(':method', $method);
            $statement->bindParam(':status', $status);
            $statement->bindParam(':paymentDate', $paymentDate);
            $statement->bindParam(':cardLast4', $cardLast4);
            $statement->bindParam(':referenceNumber', $referenceNumber);
            
            $success = $statement->execute();
            
            // If this was an insert, get the new ID and set it on the payment
            if ($success && !$payment->getId()) {
                $newId = $this->connection->lastInsertId();
                $payment->setId($newId);
            }
            
            return $success;
        } catch (Exception $e) {
            error_log("Error saving payment: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update payment status
     * 
     * @param int $id Payment ID
     * @param string $status New status
     * @return bool True on success, false on failure
     */
    public function updatePaymentStatus($id, $status) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "UPDATE payments SET status = :status WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':status', $status);
            
            return $statement->execute();
        } catch (Exception $e) {
            error_log("Error updating payment status: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete payment
     * 
     * @param int $id Payment ID
     * @return bool True on success, false on failure
     */
    public function deletePayment($id) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "DELETE FROM payments WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $statement->execute();
        } catch (Exception $e) {
            error_log("Error deleting payment: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create Payment object from database data
     * 
     * @param array $data Payment data from database
     * @return Payment Payment object
     */
    private function createPaymentFromData($data) {
        $payment = new Payment();
        
        // Set payment properties
        if (isset($data['id'])) $payment->setId($data['id']);
        if (isset($data['userId'])) $payment->setUserId($data['userId']);
        if (isset($data['transactionId'])) $payment->setTransactionId($data['transactionId']);
        if (isset($data['amount'])) $payment->setAmount($data['amount']);
        if (isset($data['method'])) $payment->setMethod($data['method']);
        if (isset($data['status'])) $payment->setStatus($data['status']);
        if (isset($data['paymentDate'])) $payment->setPaymentDate($data['paymentDate']);
        if (isset($data['cardLast4'])) $payment->setCardLast4($data['cardLast4']);
        if (isset($data['referenceNumber'])) $payment->setReferenceNumber($data['referenceNumber']);
        
        return $payment;
    }
}
