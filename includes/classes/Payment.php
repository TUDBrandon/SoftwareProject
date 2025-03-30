<?php
/**
 * Payment class
 * 
 * Represents a payment in the TechTrade system
 * Processes transactions
 */
class Payment {
    // Private properties - encapsulation
    private $id;
    private $transactionId;
    private $amount;
    private $method;
    private $status;
    private $createdAt;
    private $processedAt;
    
    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    
    // Payment method constants
    const METHOD_CREDIT_CARD = 'credit_card';
    const METHOD_DEBIT_CARD = 'debit_card';
    const METHOD_PAYPAL = 'paypal';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    
    /**
     * Constructor
     * 
     * @param array $data Payment data
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? $data['payment_id'] ?? null;
        $this->transactionId = $data['transaction_id'] ?? $data['transactionId'] ?? null;
        $this->amount = $data['amount'] ?? 0;
        $this->method = $data['method'] ?? self::METHOD_CREDIT_CARD;
        $this->status = $data['status'] ?? self::STATUS_PENDING;
        $this->createdAt = $data['created_at'] ?? $data['createdAt'] ?? date('Y-m-d H:i:s');
        $this->processedAt = $data['processed_at'] ?? $data['processedAt'] ?? null;
    }
    
    /**
     * Get payment ID
     * 
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Get transaction ID
     * 
     * @return int|null
     */
    public function getTransactionId() {
        return $this->transactionId;
    }
    
    /**
     * Get payment amount
     * 
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }
    
    /**
     * Get payment method
     * 
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }
    
    /**
     * Set payment method
     * 
     * @param string $method
     * @return self
     */
    public function setMethod(string $method) {
        // Validate method
        if (!in_array($method, [
            self::METHOD_CREDIT_CARD,
            self::METHOD_DEBIT_CARD,
            self::METHOD_PAYPAL,
            self::METHOD_BANK_TRANSFER
        ])) {
            return $this;
        }
        
        $this->method = $method;
        return $this;
    }
    
    /**
     * Get payment status
     * 
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * Set payment status
     * 
     * @param string $status
     * @return self
     */
    public function setStatus(string $status) {
        // Validate status
        if (!in_array($status, [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_COMPLETED,
            self::STATUS_FAILED
        ])) {
            return $this;
        }
        
        $this->status = $status;
        
        // Set processed date if completing or failing
        if (in_array($status, [self::STATUS_COMPLETED, self::STATUS_FAILED])) {
            $this->processedAt = date('Y-m-d H:i:s');
        }
        
        return $this;
    }
    
    /**
     * Get payment created date
     * 
     * @return string
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }
    
    /**
     * Get payment processed date
     * 
     * @return string|null
     */
    public function getProcessedAt() {
        return $this->processedAt;
    }
    
    /**
     * Convert to array
     * 
     * @return array
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transactionId,
            'amount' => $this->amount,
            'method' => $this->method,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'processed_at' => $this->processedAt
        ];
    }
}
