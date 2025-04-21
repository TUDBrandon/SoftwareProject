<?php
/**
 * Submission class
 * 
 * Represents a submission in the TechTrade system
 * Submitted by Customers, managed by Employees
 */
class Submission {
    // Private properties - encapsulation
    private $id;
    private $customerName;
    private $email;
    private $title;
    private $category;
    private $description;
    private $images;
    private $status;
    private $managedBy;
    private $customerId; // New property to store customer ID
    
    // Status constants
    const STATUS_PENDING = 'Pending';
    const STATUS_UNDER_REVIEW = 'Under Review';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';
    
    /**
     * Constructor
     * 
     * @param array $data Submission data
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? $data['submission_id'] ?? null;
        $this->customerName = $data['customer_name'] ?? $data['username'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->title = $data['title'] ?? '';
        $this->category = $data['category'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->images = $data['images'] ?? $data['item_image'] ?? '';
        $this->status = $data['status'] ?? $data['status_update'] ?? self::STATUS_PENDING;
        $this->managedBy = $data['managed_by'] ?? null;
        $this->customerId = $data['customer_id'] ?? null; // Initialize customer ID
    }
    
    /**
     * Get submission ID
     * 
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Set submission ID
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
     * Get email
     * 
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }
    
    /**
     * Set email
     * 
     * @param string $email
     * @return self
     */
    public function setEmail(string $email) {
        $this->email = $email;
        return $this;
    }
    
    /**
     * Get submission title
     * 
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }
    
    /**
     * Set submission title
     * 
     * @param string $title
     * @return self
     */
    public function setTitle(string $title) {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Get category
     * 
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }
    
    /**
     * Set category
     * 
     * @param string $category
     * @return self
     */
    public function setCategory(string $category) {
        $this->category = $category;
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
     * Get images
     * 
     * @return string
     */
    public function getImages() {
        return $this->images;
    }
    
    /**
     * Set images
     * 
     * @param string $images
     * @return self
     */
    public function setImages(string $images) {
        $this->images = $images;
        return $this;
    }
    
    /**
     * Get submission status
     * 
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * Get ID of employee managing this submission
     * 
     * @return int|null
     */
    public function getManagedBy() {
        return $this->managedBy;
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
     * Update submission status
     * 
     * @param string $status New status
     * @param int $employeeId ID of employee updating the status
     * @param string $notes Optional notes
     * @return bool
     */
    public function updateStatus(string $status, int $employeeId, string $notes = '') {
        // Validate status
        if (!in_array($status, [
            self::STATUS_PENDING,
            self::STATUS_UNDER_REVIEW,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED
        ])) {
            return false;
        }
        
        $this->status = $status;
        $this->managedBy = $employeeId;
        
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
            'email' => $this->email,
            'title' => $this->title,
            'category' => $this->category,
            'description' => $this->description,
            'images' => $this->images,
            'status' => $this->status,
            'managed_by' => $this->managedBy,
            'customer_id' => $this->customerId
        ];
    }
}
