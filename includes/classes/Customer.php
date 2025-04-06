<?php
/**
 * Customer class
 * 
 * Represents a customer user in the TechTrade system
 * Inherits from User class
 */
class Customer extends User {
    // Additional properties specific to customers
    private $address;
    private $phone;
    
    /**
     * Constructor
     * 
     * @param array $data Customer data
     */
    public function __construct(array $data = []) {
        // Call parent constructor to initialize base User properties
        parent::__construct($data);
        
        // Initialize Customer-specific properties
        $this->address = $data['address'] ?? '';
        $this->phone = $data['phone'] ?? '';
    }
    
    /**
     * Get customer address
     * 
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }
    
    /**
     * Set customer address
     * 
     * @param string $address
     * @return self
     */
    public function setAddress(string $address) {
        $this->address = $address;
        return $this;
    }
    
    /**
     * Get customer phone
     * 
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }
    
    /**
     * Set customer phone
     * 
     * @param string $phone
     * @return self
     */
    public function setPhone(string $phone) {
        $this->phone = $phone;
        return $this;
    }
    
    /**
     * Convert to array (including parent properties)
     * 
     * @return array
     */
    public function toArray() {
        return array_merge(parent::toArray(), [
            'address' => $this->address,
            'phone' => $this->phone
        ]);
    }
    
    /**
     * Submit a new submission
     * 
     * @param array $data Submission data
     * @return Submission
     */
    public function submitSubmission(array $data) {
        $data['customer_id'] = $this->getId();
        $data['customer_name'] = $this->getUsername();
        $data['email'] = $this->getEmail();
        return new Submission($data);
    }
    
    /**
     * Create a new report
     * 
     * @param array $data Report data
     * @return Report
     */
    public function createReport(array $data) {
        $data['customer_id'] = $this->getId();
        $data['customer_name'] = $this->getUsername();
        return new Report($data);
    }
    
    /**
     * Get customer's submissions
     * 
     * @return array Array of Submission objects
     */
    public function getSubmissions() {
        try {
            require_once __DIR__ . '/../../src/DBconnect.php';
            global $connection;
            
            $repository = new SubmissionRepository($connection);
            return $repository->getSubmissionsByCustomerId($this->getId());
        } catch (Exception $e) {
            error_log("Error retrieving customer submissions: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get customer's reports
     * 
     * @return array Array of Report objects
     */
    public function getReports() {
        try {
            require_once __DIR__ . '/../../src/DBconnect.php';
            global $connection;
            
            $repository = new ReportRepository($connection);
            return $repository->getReportsByCustomerId($this->getId());
        } catch (Exception $e) {
            error_log("Error retrieving customer reports: " . $e->getMessage());
            return [];
        }
    }
}
