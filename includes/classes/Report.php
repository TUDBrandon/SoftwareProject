<?php
/**
 * Report class
 * 
 * Represents a report in the TechTrade system
 * Reported by Users, handled by Employees
 */
class Report {
    // Private properties - encapsulation
    private $id;
    private $customerName;
    private $employeeCode;
    private $title;
    private $date;
    private $expectation;
    private $description;
    private $technical;
    private $itemImage;
    private $receiptImage;
    private $status;
    private $employeeId;
    private $customerId;
    
    // Status constants
    const STATUS_PENDING = 'Pending';
    const STATUS_UNDER_REVIEW = 'Under Review';
    const STATUS_RESOLVED = 'Resolved';
    const STATUS_REJECTED = 'Rejected';
    
    /**
     * Constructor
     * 
     * @param array $data Report data
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? $data['report_id'] ?? null;
        $this->customerName = $data['customer_name'] ?? $data['username'] ?? '';
        $this->employeeCode = $data['employee_code'] ?? '';
        $this->title = $data['title'] ?? '';
        $this->date = $data['date'] ?? '';
        $this->expectation = $data['expectation'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->technical = $data['technical'] ?? '';
        $this->itemImage = $data['item_image'] ?? '';
        $this->receiptImage = $data['receipt_image'] ?? '';
        $this->status = $data['status'] ?? $data['status_update'] ?? self::STATUS_PENDING;
        $this->employeeId = $data['employee_id'] ?? null;
        $this->customerId = $data['customer_id'] ?? null;
    }
    
    /**
     * Get report ID
     * 
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Set report ID
     * 
     * @param int $id
     * @return self
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    /**
     * Get customer name
     * 
     * @return string
     */
    public function getCustomerName() {
        return $this->customerName;
    }
    
    /**
     * Set customer name
     * 
     * @param string $customerName
     * @return self
     */
    public function setCustomerName(string $customerName) {
        $this->customerName = $customerName;
        return $this;
    }
    
    /**
     * Get employee code
     * 
     * @return string
     */
    public function getEmployeeCode() {
        return $this->employeeCode;
    }
    
    /**
     * Set employee code
     * 
     * @param string $employeeCode
     * @return self
     */
    public function setEmployeeCode(string $employeeCode) {
        $this->employeeCode = $employeeCode;
        return $this;
    }
    
    /**
     * Get report title
     * 
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }
    
    /**
     * Set report title
     * 
     * @param string $title
     * @return self
     */
    public function setTitle(string $title) {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Get purchase date
     * 
     * @return string
     */
    public function getDate() {
        return $this->date;
    }
    
    /**
     * Set purchase date
     * 
     * @param string $date
     * @return self
     */
    public function setDate(string $date) {
        $this->date = $date;
        return $this;
    }
    
    /**
     * Get expectation
     * 
     * @return string
     */
    public function getExpectation() {
        return $this->expectation;
    }
    
    /**
     * Set expectation
     * 
     * @param string $expectation
     * @return self
     */
    public function setExpectation(string $expectation) {
        $this->expectation = $expectation;
        return $this;
    }
    
    /**
     * Get description
     * 
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }
    
    /**
     * Set description
     * 
     * @param string $description
     * @return self
     */
    public function setDescription(string $description) {
        $this->description = $description;
        return $this;
    }
    
    /**
     * Get technical details
     * 
     * @return string
     */
    public function getTechnical() {
        return $this->technical;
    }
    
    /**
     * Set technical details
     * 
     * @param string $technical
     * @return self
     */
    public function setTechnical(string $technical) {
        $this->technical = $technical;
        return $this;
    }
    
    /**
     * Get item image
     * 
     * @return string
     */
    public function getItemImage() {
        return $this->itemImage;
    }
    
    /**
     * Set item image
     * 
     * @param string $itemImage
     * @return self
     */
    public function setItemImage(string $itemImage) {
        $this->itemImage = $itemImage;
        return $this;
    }
    
    /**
     * Get receipt image
     * 
     * @return string
     */
    public function getReceiptImage() {
        return $this->receiptImage;
    }
    
    /**
     * Set receipt image
     * 
     * @param string $receiptImage
     * @return self
     */
    public function setReceiptImage(string $receiptImage) {
        $this->receiptImage = $receiptImage;
        return $this;
    }
    
    /**
     * Get report status
     * 
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * Set report status
     * 
     * @param string $status
     * @return self
     */
    public function setStatus(string $status) {
        // Validate status
        if (!in_array($status, [
            self::STATUS_PENDING,
            self::STATUS_UNDER_REVIEW,
            self::STATUS_RESOLVED,
            self::STATUS_REJECTED
        ])) {
            return $this;
        }
        
        $this->status = $status;
        return $this;
    }
    
    /**
     * Get employee ID
     * 
     * @return int|null
     */
    public function getEmployeeId() {
        return $this->employeeId;
    }
    
    /**
     * Set employee ID
     * 
     * @param int|null $employeeId
     * @return self
     */
    public function setEmployeeId($employeeId) {
        $this->employeeId = $employeeId;
        return $this;
    }
    
    /**
     * Get customer ID
     * 
     * @return int|null
     */
    public function getCustomerId() {
        return $this->customerId;
    }
    
    /**
     * Set customer ID
     * 
     * @param int $customerId
     * @return self
     */
    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
        return $this;
    }
    
    /**
     * Resolve report
     * 
     * @param int $employeeId ID of employee resolving the report
     * @param string $resolution Resolution description
     * @return bool
     */
    public function resolve(int $employeeId, string $resolution) {
        $this->employeeId = $employeeId;
        $this->status = self::STATUS_RESOLVED;
        return true;
    }
    
    /**
     * Convert to array
     * 
     * @return array
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'customer_name' => $this->customerName,
            'employee_code' => $this->employeeCode,
            'title' => $this->title,
            'date' => $this->date,
            'expectation' => $this->expectation,
            'description' => $this->description,
            'technical' => $this->technical,
            'item_image' => $this->itemImage,
            'receipt_image' => $this->receiptImage,
            'status' => $this->status,
            'employee_id' => $this->employeeId,
            'customer_id' => $this->customerId
        ];
    }
}
