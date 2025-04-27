<?php
/**
 * ReportRepository class
 * 
 * Handles database operations for reports
 */
require_once __DIR__ . '/Report.php';

class ReportRepository {
    private $connection;
    
    /**
     * Constructor
     * 
     * @param PDO $connection Database connection
     */
    public function __construct($connection = null) {
        if ($connection) {
            $this->connection = $connection;
        } else {
            // Try to establish connection if not provided
            try {
                require_once __DIR__ . '/../../src/DBconnect.php';
                global $connection;
                $this->connection = $connection;
            } catch (Exception $e) {
                error_log("Error connecting to database: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Get all reports
     * 
     * @return array Array of Report objects
     */
    public function getAllReports() {
        $reports = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM report";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $reports[] = $this->createReportFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving reports: " . $e->getMessage());
        }
        
        return $reports;
    }
    
    /**
     * Get report by ID
     * 
     * @param int $id Report ID
     * @return Report|null Report object or null if not found
     */
    public function getReportById($id) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM report WHERE report_id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $this->createReportFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving report by ID: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Get reports by employee ID
     * 
     * @param int $employeeId Employee ID
     * @return array Array of Report objects
     */
    public function getReportsByEmployeeId($employeeId) {
        $reports = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM report WHERE employee_id = :employeeId";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $reports[] = $this->createReportFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving reports by employee ID: " . $e->getMessage());
        }
        
        return $reports;
    }
    
    /**
     * Get reports by status
     * 
     * @param string $status Report status
     * @return array Array of Report objects
     */
    public function getReportsByStatus($status) {
        $reports = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM report WHERE status_update = :status";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':status', $status);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $reports[] = $this->createReportFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving reports by status: " . $e->getMessage());
        }
        
        return $reports;
    }
    
    /**
     * Get reports by customer ID
     * 
     * @param int $customerId The customer ID
     * @return array Array of Report objects
     */
    public function getReportsByCustomerId($customerId) {
        $reports = [];
        
        try {
            $stmt = $this->connection->prepare("SELECT * FROM report WHERE customer_id = :customer_id ORDER BY timestamps DESC");
            $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $row) {
                $reports[] = new Report([
                    'id' => $row['report_id'],
                    'customer_id' => $row['customer_id'],
                    'customer_name' => $row['username'],
                    'title' => $row['title'],
                    'date' => $row['date'],
                    'expectation' => $row['expectation'],
                    'description' => $row['description'],
                    'technical' => $row['technical'],
                    'item_image' => $row['item_image'],
                    'receipt_image' => $row['receipt_image'],
                    'status' => $row['status_update'],
                    'employee_id' => $row['employee_id']
                ]);
            }
        } catch (PDOException $e) {
            error_log("Error getting reports by customer ID: " . $e->getMessage());
        }
        
        return $reports;
    }
    
    /**
     * Save a report to the database
     * 
     * @param Report $report The report to save
     * @return bool True if successful, false otherwise
     */
    public function saveReport(Report $report) {
        try {
            // If the report has an ID, update it, otherwise insert a new one
            if ($report->getId()) {
                $stmt = $this->connection->prepare("
                    UPDATE report 
                    SET username = :username, 
                        title = :title, 
                        date = :date, 
                        expectation = :expectation, 
                        description = :description, 
                        technical = :technical, 
                        item_image = :item_image, 
                        receipt_image = :receipt_image, 
                        status_update = :status,
                        employee_id = :employee_id,
                        customer_id = :customer_id
                    WHERE report_id = :id
                ");
                $stmt->bindParam(':id', $report->getId(), PDO::PARAM_INT);
            } else {
                $stmt = $this->connection->prepare("
                    INSERT INTO report 
                    (username, title, date, expectation, description, technical, item_image, receipt_image, status_update, employee_id, customer_id) 
                    VALUES 
                    (:username, :title, :date, :expectation, :description, :technical, :item_image, :receipt_image, :status, :employee_id, :customer_id)
                ");
            }
            
            $username = $report->getCustomerName();
            $title = $report->getTitle();
            $date = $report->getDate();
            $expectation = $report->getExpectation();
            $description = $report->getDescription();
            $technical = $report->getTechnical();
            $itemImage = $report->getItemImage();
            $receiptImage = $report->getReceiptImage();
            $status = $report->getStatus();
            $employeeId = $report->getEmployeeId();
            $customerId = $report->getCustomerId();
            
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':expectation', $expectation);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':technical', $technical);
            $stmt->bindParam(':item_image', $itemImage);
            $stmt->bindParam(':receipt_image', $receiptImage);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
            $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            
            // If this was an insert, get the new ID and set it on the report
            if (!$report->getId() && $result) {
                $report->setId($this->connection->lastInsertId());
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error saving report: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update report status
     * 
     * @param int $id Report ID
     * @param string $status New status
     * @return bool True on success, false on failure
     */
    public function updateReportStatus($id, $status) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "UPDATE report SET status_update = :status WHERE report_id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':status', $status);
            
            return $statement->execute();
        } catch (Exception $e) {
            error_log("Error updating report status: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Assign report to employee
     * 
     * @param int $id Report ID
     * @param int $employeeId Employee ID
     * @return bool True on success, false on failure
     */
    public function assignReportToEmployee($id, $employeeId) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "UPDATE report SET employee_id = :employeeId WHERE report_id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
            
            return $statement->execute();
        } catch (Exception $e) {
            error_log("Error assigning report to employee: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete report
     * 
     * @param int $id Report ID
     * @return bool True on success, false on failure
     */
    public function deleteReport($id) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "DELETE FROM report WHERE report_id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $statement->execute();
        } catch (Exception $e) {
            error_log("Error deleting report: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create Report object from database data
     * 
     * @param array $data Report data from database
     * @return Report Report object
     */
    private function createReportFromData($data) {
        $report = new Report();
        
        // Set report properties based on the updated Report class fields
        if (isset($data['report_id'])) $report->setId($data['report_id']);
        if (isset($data['username'])) $report->setCustomerName($data['username']);
        if (isset($data['employee_id'])) $report->setEmployeeId($data['employee_id']);
        if (isset($data['title'])) $report->setTitle($data['title']);
        if (isset($data['date'])) $report->setDate($data['date']);
        if (isset($data['expectation'])) $report->setExpectation($data['expectation']);
        if (isset($data['description'])) $report->setDescription($data['description']);
        if (isset($data['technical'])) $report->setTechnical($data['technical']);
        if (isset($data['item_image'])) $report->setItemImage($data['item_image']);
        if (isset($data['receipt_image'])) $report->setReceiptImage($data['receipt_image']);
        if (isset($data['status_update'])) $report->setStatus($data['status_update']);
        if (isset($data['customer_id'])) $report->setCustomerId($data['customer_id']);
        
        return $report;
    }
}
