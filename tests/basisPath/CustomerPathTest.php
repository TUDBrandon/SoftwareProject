<?php
require_once __DIR__ . '/../../includes/classes/User.php';
require_once __DIR__ . '/../../includes/classes/Customer.php';

class CustomerPathTest {
    public function runTests() {
        $this->testNullData();
        $this->testEmptyData();
        $this->testInvalidData();
        $this->testValidData();

        echo "All CustomerPath Tests Passed!\n";
    }

    private function testNullData() {
        // Customer constructor requires an array, so we pass an empty array instead of null
        $customer = new Customer([]);
        assert($customer->getId() === null, "getId() with empty data should return null");

        echo "Null Data Test passed!\n";
    }

    private function testEmptyData() {
        $customer = new Customer([]);
        assert($customer->getUsername() === '', "getUsername() with empty data should return empty string");
        assert($customer->getEmail() === '', "getEmail() with empty data should return empty string");
        
        echo "Empty Data Test passed!\n";
    }
    
    private function testInvalidData() {
        $customer = new Customer([
            'invalid_key' => 'value'
        ]);
        assert($customer->getId() === null, "getId() with invalid data should return null");
        assert($customer->getUsername() === '', "getUsername() with invalid data should return empty string");
        
        echo "Invalid Data Test passed!\n";
    }
    
    private function testValidData() {
        $customer = new Customer([
            'id' => 1,
            'username' => 'testuser',
            'email' => 'test@example.com',
            'first_name' => 'Test'
        ]);
        assert($customer->getId() === 1, "getId() with valid data failed");
        assert($customer->getUsername() === 'testuser', "getUsername() with valid data failed");
        assert($customer->getEmail() === 'test@example.com', "getEmail() with valid data failed");
        
        echo "Valid Data Test passed!\n";
    }
}
?>
