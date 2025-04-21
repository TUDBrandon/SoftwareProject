<?php
// tests/unit/SubmissionTest.php
require_once __DIR__ . '/../../includes/classes/Submission.php';

class SubmissionTest {
    private $submission;
    
    public function __construct() {
        $this->submission = new Submission([
            'id' => 1,
            'customer_name' => 'Test User',
            'email' => 'test@example.com',
            'title' => 'Test Submission',
            'category' => 'hardware',
            'description' => 'Test description',
            'images' => 'test.jpg',
            'status' => 'Pending',
            'customer_id' => 1
        ]);
    }
    
    public function runTests() {
        $this->testGetters();
        $this->testStatusUpdate();
        
        echo "All Submission tests passed!\n";
    }
    
    private function testGetters() {
        assert($this->submission->getId() === 1, "getId() failed");
        assert($this->submission->getCustomerName() === 'Test User', "getCustomerName() failed");
        assert($this->submission->getEmail() === 'test@example.com', "getEmail() failed");
        assert($this->submission->getTitle() === 'Test Submission', "getTitle() failed");
        assert($this->submission->getCategory() === 'hardware', "getCategory() failed");
        assert($this->submission->getCustomerId() === 1, "getCustomerId() failed");
        
        echo "Submission getter tests passed!\n";
    }
    
    private function testStatusUpdate() {
        $this->submission->setStatus('Under Review');
        assert($this->submission->getStatus() === 'Under Review', "Status update failed");
        
        echo "Submission status update tests passed!\n";
    }
}
?>
