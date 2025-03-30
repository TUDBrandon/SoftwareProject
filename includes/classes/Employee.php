<?php
/**
 * Employee class
 * 
 * Represents an employee user in the TechTrade system
 * Inherits from User class
 */
class Employee extends User {
    /**
     * Constructor
     * 
     * @param array $data Employee data
     */
    public function __construct(array $data = []) {
        // Call parent constructor to initialize base User properties
        parent::__construct($data);
    }
    
    /**
     * Convert to array (including parent properties)
     * 
     * @return array
     */
    public function toArray() {
        return parent::toArray();
    }
    
    /**
     * Handle a report
     * 
     * @param Report $report
     * @param string $resolution
     * @return bool
     */
    public function handleReport(Report $report, string $resolution) {
        return $report->resolve($this->getId(), $resolution);
    }
    
    /**
     * Manage a submission
     * 
     * @param Submission $submission
     * @param string $status New status
     * @param string $notes Employee notes
     * @return bool
     */
    public function manageSubmission(Submission $submission, string $status, string $notes = '') {
        return $submission->updateStatus($status, $this->getId(), $notes);
    }
}
