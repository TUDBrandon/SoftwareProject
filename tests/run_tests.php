<?php
require_once __DIR__ . '/unit/CustomerTest.php';
require_once __DIR__ . '/unit/SubmissionRepositoryTest.php';
require_once __DIR__ . '/unit/ReportRepositoryTest.php';
require_once __DIR__ . '/unit/ReportTest.php';
require_once __DIR__ . '/unit/SubmissionTest.php';
require_once __DIR__ . '/basisPath/CustomerPathTest.php';
require_once __DIR__ . '/equivalence/validationTest.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Running all tests...\n";

$customerTest = new CustomerTest();
$customerTest->runTests();

$submissionRepositoryTest = new SubmissionRepositoryTest();
$submissionRepositoryTest->runTests();

$reportRepositoryTest = new ReportRepositoryTest();
$reportRepositoryTest->runTests();

$reportTest = new ReportTest();
$reportTest->runTests();

$submissionTest = new SubmissionTest();
$submissionTest->runTests();

$customerPathTest = new CustomerPathTest();
$customerPathTest->runTests();

$validationTest = new ValidatonTest();
$validationTest->runTests();

echo "All tests passed!\n";
?>