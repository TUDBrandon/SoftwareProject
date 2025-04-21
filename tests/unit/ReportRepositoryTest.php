<?php
// tests/unit/ReportRepositoryTest.php
require_once __DIR__ . '/../../includes/classes/Report.php';
require_once __DIR__ . '/../../includes/classes/ReportRepository.php';

class ReportRepositoryTest {
    private $repository;
    private $testConnection;

    public function __construct() {
        $this->testConnection = $this->createTestPDO();
        $this->repository = new ReportRepository($this->testConnection);
    }

    private function createTestPDO() {
        return new class {
            public $lastInsertId = 1;
            public $executed = false;
            public $boundParams = [];

            public function prepare() {
                return $this;
            }

            public function bindParam($param, &$value, $type = null) {
                $this->boundParams[$param] = $value;
                return true;
            }

            public function execute() {
                $this->executed = true;
                return true;
            }

            public function lastInsertId() {
                return $this->lastInsertId;
            }
        };
    }

    public function runTests() {
        $this->testSaveReport();

        echo "All ReportRepository Tests Passed!\n";
    }

    private function testSaveReport() {
        $report = new Report([
            'customer_name' => 'Test User',
            'employee_code' => '123',
            'title' => 'Test Report',
            'date' => '2025-04-21',
            'expectation' => 'Test expectation',
            'description' => 'Test description',
            'technical' => 'Test technical details',
            'customer_id' => 1
        ]);

        $result = $this->repository->saveReport($report);
        assert($this->testConnection->executed, "Execute should be called when saving a report");
        assert($this->testConnection->boundParams[':customer_id'] === 1, "Customer ID should be bound correctly");

        echo "Save Report Tests Passed!\n";
    }
}

// Don't run tests automatically when included
// Let run_tests.php handle this
?>
