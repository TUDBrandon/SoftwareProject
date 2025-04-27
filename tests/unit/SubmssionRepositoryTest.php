<?php
// tests/unit/SubmssionRepositoryTest.php
require_once __DIR__ . '/../../includes/classes/Submission.php';
require_once __DIR__ . '/../../includes/classes/SubmissionRepository.php';

class SubmssionRepositoryTest {
    private $repository;
    private $testConnection;

    public function __construct() {
        $this->testConnection = $this->createTestPDO();
        $this->repository = new SubmissionRepository($this->testConnection);
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
        $this->testSaveSubmission();

        echo "All SubmissionRepository Tests Passed!\n";
    }

    private function testSaveSubmission() {
        $submission = new Submission([
            'customer_name' => 'Test User',
            'email' => 'test@example.com',
            'title' => 'Test Submission',
            'category' => 'hardware',
            'description' => 'Test description',
            'customer_id' => 1
        ]);

        $result = $this->repository->saveSubmission($submission);
        assert($this->testConnection->executed, "Execute should be called when saving a submission");
        assert($this->testConnection->boundParams[':customer_id'] === 1, "Customer ID should be bound correctly");

        echo "Save Submission Tests Passed!\n";
    }
}
?>
