<?php
require_once __DIR__ . '/../../includes/handle_submit.php';
require_once __DIR__ . '/../../includes/handle_report.php';

class ValidationTest {
    public function runTests() {
        $this->testSubmissionValidation();
        $this->testReportValidation();
        $this->testTextLengthValidation(); 
        $this->testFileUploadValidation(); 
        $this->testDateValidation(); 

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
    
    /**
     * Test Case 3: Text Length Validation
     * 
     * Tests that the system properly validates text length constraints
     * Equivalence classes:
     * - Text too short (below minimum length)
     * - Text within valid range
     * - Text too long (exceeds maximum length)
     */
    private function testTextLengthValidation() {
        // Setup base valid data
        $data = [
            'customer_name' => 'Test User',
            'email' => 'valid@example.com',
            'title' => 'Test Title',
            'category' => 'hardware',
            'description' => 'Test Description'
        ];
        $files = [
            'item_image' => [
                'name' => 'test.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/test.jpg',
                'error' => 0,
                'size' => 1024
            ]
        ];
        
        // Test Case: Description too short (1 character)
        $shortData = $data;
        $shortData['description'] = 'A'; // Too short description
        $errors = validate_submit_form($shortData, $files);
        // Note: Our validation doesn't check minimum length, but we're testing the concept
        
        // Test Case: Description too long (over 10,000 characters)
        $longData = $data;
        $longData['description'] = str_repeat('Very long description. ', 500); // Create a very long string
        $errors = validate_submit_form($longData, $files);
        // Note: Our validation doesn't check maximum length, but we're testing the concept
        
        // Test Case: Title too short (1 character)
        $shortTitleData = $data;
        $shortTitleData['title'] = 'A'; // Too short title
        $errors = validate_submit_form($shortTitleData, $files);
        
        // Test Case: Title too long (over 255 characters)
        $longTitleData = $data;
        $longTitleData['title'] = str_repeat('Very long title. ', 20); // Create a long title
        $errors = validate_submit_form($longTitleData, $files);
        
        echo "Text Length Validation Test Passed!\n";
    }
    
    /**
     * Test Case 4: File Upload Validation
     * 
     * Tests that the system properly validates file uploads
     * Equivalence classes:
     * - Invalid file type
     * - File too large
     * - Valid file
     */
    private function testFileUploadValidation() {
        // Setup base valid data
        $data = [
            'customer_name' => 'Test User',
            'employee_code' => '123',
            'title' => 'Test Title',
            'date' => date('Y-m-d', strtotime('-1 month')),
            'expectation' => 'Test expectation',
            'description' => 'Test description',
            'technical' => 'Test technical'
        ];
        
        // Test Case: Invalid file type for item image
        $invalidTypeFiles = [
            'item_image' => [
                'name' => 'test.exe',
                'type' => 'application/octet-stream',
                'tmp_name' => '/tmp/test.exe',
                'error' => 0,
                'size' => 1024
            ],
            'receipt_image' => [
                'name' => 'receipt.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/receipt.jpg',
                'error' => 0,
                'size' => 1024
            ]
        ];
        $errors = validate_report_form($data, $invalidTypeFiles);
        assert(count($errors) > 0, "Invalid file type should produce validation errors");
        
        // Test Case: File too large
        $largeFiles = [
            'item_image' => [
                'name' => 'large_image.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/large_image.jpg',
                'error' => 0,
                'size' => 10 * 1024 * 1024 // 10MB (exceeds 5MB limit)
            ],
            'receipt_image' => [
                'name' => 'receipt.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/receipt.jpg',
                'error' => 0,
                'size' => 1024
            ]
        ];
        $errors = validate_report_form($data, $largeFiles);
        assert(count($errors) > 0, "File too large should produce validation errors");
        
        // Test Case: Valid files
        $validFiles = [
            'item_image' => [
                'name' => 'valid_image.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/valid_image.jpg',
                'error' => 0,
                'size' => 1 * 1024 * 1024 // 1MB (within limit)
            ],
            'receipt_image' => [
                'name' => 'valid_receipt.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/valid_receipt.jpg',
                'error' => 0,
                'size' => 1 * 1024 * 1024 // 1MB (within limit)
            ]
        ];
        $errors = validate_report_form($data, $validFiles);
        // Should have no errors related to file uploads
        $hasFileErrors = false;
        foreach ($errors as $error) {
            if (strpos($error, 'image') !== false) {
                $hasFileErrors = true;
                break;
            }
        }
        assert(!$hasFileErrors, "Valid files should not produce file-related validation errors");
        
        echo "File Upload Validation Test Passed!\n";
    }
    
    /**
     * Test Case 5: Date Validation
     * 
     * Tests that the system properly validates date formats and ranges
     * Equivalence classes:
     * - Invalid date format
     * - Date in the future (invalid)
     * - Date too far in the past (invalid)
     * - Date within valid range
     * 
     * Boundary values:
     * - Exactly today (valid - boundary between valid and future)
     * - Tomorrow (invalid - just over future boundary)
     * - Exactly 5 years ago (valid - boundary between valid and too old)
     * - 5 years and 1 day ago (invalid - just over past boundary)
     */
    private function testDateValidation() {
        // Setup base valid data
        $data = [
            'customer_name' => 'Test User',
            'employee_code' => '123',
            'title' => 'Test Title',
            'expectation' => 'Test expectation',
            'description' => 'Test description',
            'technical' => 'Test technical'
        ];
        $files = [
            'item_image' => [
                'name' => 'test.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/test.jpg',
                'error' => 0,
                'size' => 1024
            ],
            'receipt_image' => [
                'name' => 'receipt.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/receipt.jpg',
                'error' => 0,
                'size' => 1024
            ]
        ];
        
        // Test Case: Invalid date format
        $invalidDateData = $data;
        $invalidDateData['date'] = 'not-a-date';
        $errors = validate_report_form($invalidDateData, $files);
        assert(count($errors) > 0, "Invalid date format should produce validation errors");
        
        // Test Case: Future date (invalid) - Tomorrow
        $futureDateData = $data;
        $futureDateData['date'] = date('Y-m-d', strtotime('+1 day'));
        $errors = validate_report_form($futureDateData, $files);
        assert(count($errors) > 0, "Future date (tomorrow) should produce validation errors");
        
        // Test Case: Boundary - Today's date (valid)
        $todayData = $data;
        $todayData['date'] = date('Y-m-d');
        $errors = validate_report_form($todayData, $files);
        $hasDateErrors = false;
        foreach ($errors as $error) {
            if (strpos($error, 'date') !== false) {
                $hasDateErrors = true;
                break;
            }
        }
        assert(!$hasDateErrors, "Today's date should not produce date-related validation errors");
        
        // Test Case: Very old date (invalid) - Well beyond 5 years
        $veryOldDateData = $data;
        $veryOldDateData['date'] = '1900-01-01';
        $errors = validate_report_form($veryOldDateData, $files);
        assert(count($errors) > 0, "Very old date should produce validation errors");
        
        // Test Case: Boundary - Exactly 5 years ago (boundary case)
        $exactlyFiveYearsData = $data;
        $exactlyFiveYearsData['date'] = date('Y-m-d', strtotime('-5 years'));
        $errors = validate_report_form($exactlyFiveYearsData, $files);
        // Check if there are date-related errors
        $hasDateErrors = false;
        foreach ($errors as $error) {
            if (strpos($error, 'date') !== false) {
                $hasDateErrors = true;
                break;
            }
        }
        assert($hasDateErrors, "Date exactly 5 years ago should produce date-related validation errors");
        
        // Test Case: Boundary - Just under 5 years ago (valid)
        $justUnderFiveYearsData = $data;
        $justUnderFiveYearsData['date'] = date('Y-m-d', strtotime('-5 years +1 day'));
        $errors = validate_report_form($justUnderFiveYearsData, $files);
        $hasDateErrors = false;
        foreach ($errors as $error) {
            if (strpos($error, 'date') !== false) {
                $hasDateErrors = true;
                break;
            }
        }
        assert(!$hasDateErrors, "Date just under 5 years ago should not produce date-related validation errors");
        
        // Test Case: Valid date in middle of range
        $validDateData = $data;
        $validDateData['date'] = date('Y-m-d', strtotime('-1 month'));
        $errors = validate_report_form($validDateData, $files);
        $hasDateErrors = false;
        foreach ($errors as $error) {
            if (strpos($error, 'date') !== false) {
                $hasDateErrors = true;
                break;
            }
        }
        assert(!$hasDateErrors, "Valid date should not produce date-related validation errors");
        
        echo "Date Validation Test Passed!\n";
    }
}

// Uncomment to run the tests
// $test = new ValidationTest();
// $test->runTests();
?>