<?php
require_once __DIR__ . '/../../includes/handle_submit.php';
require_once __DIR__ . '/../../includes/handle_report.php';

class ValidationTest {
    public function runTests() {
        $this->testSubmissionValidation();
        $this->testReportValidation();

        echo "All Validation Tests Passed!\n";
    }

    private function testSubmissionValidation() {
        //Test Case: Empty data
        $data = [];
        $files = [];
        $errors = validate_submit_form($data, $files);
        assert(count($errors) > 0, "Empty data should cause validation error");

        //Test Case: Missing required fields
        $data = ['customer_name' => 'Test User'];
        $errors = validate_submit_form($data, $files);
        assert(count($errors) > 0, "Missing required fields should cause validation error");

        //Test Case: Invalid Email
        $data = [
            'customer_name' => 'Test User',
            'email' => 'not-email',
            'title' => 'Test Title',
            'category' => 'hardware',
            'description' => 'Test Description'
        ];
        $errors = validate_submit_form($data, $files);
        assert(count($errors) > 0, "Invalid email should cause validation error");

        echo "Submissions Validation Tests Passed!\n";
    }

    private function testReportValidation() {
        // Test Case: Empty data
        $data = [];
        $files = [];
        $errors = validate_report_form($data, $files);
        assert(count($errors) > 0, "Empty data should produce validation errors");
        
        // Test Case: Future date
        $data = [
            'customer_name' => 'Test User',
            'employee_code' => '123',
            'title' => 'Test Title',
            'date' => date('Y-m-d', strtotime('+1 year')),
            'expectation' => 'Test expectation',
            'description' => 'Test description',
            'technical' => 'Test technical'
        ];
        $errors = validate_report_form($data, $files);
        assert(count($errors) > 0, "Future date should produce validation errors");
        
        echo "Report validation tests passed!\n";
    }
}
?>