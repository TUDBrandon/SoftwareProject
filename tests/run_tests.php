<?php
require_once __DIR__ . '/unit/CustomerTest.php';
require_once __DIR__ . '/unit/SubmssionRepositoryTest.php';
require_once __DIR__ . '/unit/ReportRepositoryTest.php';
require_once __DIR__ . '/unit/ReportTest.php';
require_once __DIR__ . '/unit/SubmissionTest.php';
require_once __DIR__ . '/basisPath/CustomerPathTest.php';
require_once __DIR__ . '/basisPath/SubmissionActionPathTest.php';
require_once __DIR__ . '/equivalence/validationTest.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Running all tests...\n";

$customerTest = new CustomerTest();
$customerTest->runTests();

$submssionRepositoryTest = new SubmssionRepositoryTest();
$submssionRepositoryTest->runTests();

$reportRepositoryTest = new ReportRepositoryTest();
$reportRepositoryTest->runTests();

$reportTest = new ReportTest();
$reportTest->runTests();

$submissionTest = new SubmissionTest();
$submissionTest->runTests();

$customerPathTest = new CustomerPathTest();
$customerPathTest->runTests();

$submissionActionPathTest = new SubmissionActionPathTest();
$submissionActionPathTest->runTests();

$validationTest = new ValidationTest();
$validationTest->runTests();

echo ("All tests passed!\n");
?>