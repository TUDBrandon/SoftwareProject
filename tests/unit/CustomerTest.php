<?php
require_once __DIR__ . '/../../includes/classes/User.php';
require_once __DIR__ . '/../../includes/classes/Customer.php';
require_once __DIR__ . '/../../includes/classes/Submission.php';
require_once __DIR__ . '/../../includes/classes/Report.php';

class CustomerTest {
    private $customer;

    public function __construct() {
        $this->customer = new Customer([
            'id' => 1,
            'username' => 'testuser',
            'email' => 'test@example.com',
            'first_name' => 'Test'
        ]);
    }

    public function runTests() {
        $this->testGetters();
        $this->testSubmitSubmission();
        $this->testCreateReport();

        echo "All Customer Tests Passed!\n";
    }

    private function testGetters() {
        assert($this->customer->getId() === 1, "getId() failed");
        assert($this->customer->getUsername() === 'testuser', "getUsername() failed");
        assert($this->customer->getEmail() === 'test@example.com', "getEmail() failed");
        assert($this->customer->getFirstName() === 'Test', "getFirstName() failed");
        
        echo "Getters Test Passed!\n";
    }

    private function testSubmitSubmission() {
        $submission = $this->customer->submitSubmission([
            'title' => 'Test Submission',
            'category' => 'Test Category',
            'description' => 'Test Description',
            'images' => null,
            'status_update' => 'Pending'
        ]);
        assert($submission instanceof Submission, "submitSubmission() should return a Submission object");
        assert($submission->getCustomerId() === 1, "Customer ID should be set on submission");
        assert($submission->getCustomerName() === 'testuser', "Customer Name should be set on submission");
        assert($submission->getEmail() === 'test@example.com', "Email should be set on submission");
        
        echo "SubmitSubmission Test Passed!\n";
    }

    private function testCreateReport() {
        $report = $this->customer->createReport([
            'title' => 'Test Report',
            'date' => '2025-04-20',
            'expectation' => 'Test expectation',
            'description' => 'Test description',
            'technical' => 'Test technical details',
            'employee_code' => '123'
        ]);
        
        assert($report instanceof Report, "createReport() should return a Report object");
        assert($report->getCustomerId() === 1, "Customer ID should be set on report");
        assert($report->getCustomerName() === 'testuser', "Customer name should be set on report");
        
        echo "Report tests passed!\n";
    }
}
?>