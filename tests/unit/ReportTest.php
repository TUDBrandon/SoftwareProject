<?php
// tests/unit/ReportTest.php
require_once __DIR__ . '/../../includes/classes/Report.php';

class ReportTest {
    private $report;
    
    public function __construct() {
        $this->report = new Report([
            'id' => 1,
            'customer_name' => 'Test User',
            'employee_code' => '123',
            'title' => 'Test Report',
            'date' => '2025-04-21',
            'expectation' => 'Test expectation',
            'description' => 'Test description',
            'technical' => 'Test technical details',
            'customer_id' => 1,
            'employee_id' => 2,
            'status' => 'Pending'
        ]);
    }
    
    public function runTests() {
        $this->testGetters();
        $this->testStatusUpdate();
        
        echo "All Report tests passed!\n";
    }
    
    private function testGetters() {
        assert($this->report->getId() === 1, "getId() failed");
        assert($this->report->getCustomerName() === 'Test User', "getCustomerName() failed");
        assert($this->report->getTitle() === 'Test Report', "getTitle() failed");
        assert($this->report->getCustomerId() === 1, "getCustomerId() failed");
        assert($this->report->getStatus() === 'Pending', "getStatus() failed");
        
        echo "Report getter tests passed!\n";
    }
    
    private function testStatusUpdate() {
        $this->report->setStatus('Under Review');
        assert($this->report->getStatus() === 'Under Review', "Status update failed");
        
        echo "Report status update tests passed!\n";
    }
}
?>
