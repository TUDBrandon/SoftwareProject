<?php
/**
 * SubmissionRepository class
 * 
 * Handles database operations for submissions
 */
class SubmissionRepository {
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
     * Get all submissions
     * 
     * @return array Array of Submission objects
     */
    public function getAllSubmissions() {
        $submissions = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM submission";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $submissions[] = $this->createSubmissionFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving submissions: " . $e->getMessage());
        }
        
        return $submissions;
    }
    
    /**
     * Get submission by ID
     * 
     * @param int $id Submission ID
     * @return Submission|null Submission object or null if not found
     */
    public function getSubmissionById($id) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM submission WHERE submission_id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $this->createSubmissionFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving submission by ID: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Get submissions by status
     * 
     * @param string $status Submission status
     * @return array Array of Submission objects
     */
    public function getSubmissionsByStatus($status) {
        $submissions = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM submission WHERE status_update = :status";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':status', $status);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $submissions[] = $this->createSubmissionFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving submissions by status: " . $e->getMessage());
        }
        
        return $submissions;
    }
    
    /**
     * Get submissions by category
     * 
     * @param string $category Submission category
     * @return array Array of Submission objects
     */
    public function getSubmissionsByCategory($category) {
        $submissions = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM submission WHERE category = :category";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':category', $category);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $submissions[] = $this->createSubmissionFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving submissions by category: " . $e->getMessage());
        }
        
        return $submissions;
    }
    
    /**
     * Get submissions managed by specific user
     * 
     * @param int $managerId Manager/Employee ID
     * @return array Array of Submission objects
     */
    public function getSubmissionsByManager($managerId) {
        $submissions = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM submission WHERE managedBy = :managedBy";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':managedBy', $managerId, PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $submissions[] = $this->createSubmissionFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving submissions by manager: " . $e->getMessage());
        }
        
        return $submissions;
    }
    
    /**
     * Get submissions by customer ID
     * 
     * @param int $customerId Customer ID
     * @return array Array of Submission objects
     */
    public function getSubmissionsByCustomerId($customerId) {
        $submissions = [];
        
        try {
            $stmt = $this->connection->prepare("SELECT * FROM submission WHERE customer_id = :customer_id ORDER BY timestamps DESC");
            $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $row) {
                $submissions[] = new Submission([
                    'id' => $row['submission_id'],
                    'customer_id' => $row['customer_id'],
                    'customer_name' => $row['username'],
                    'email' => $row['email'],
                    'title' => $row['title'],
                    'category' => $row['category'],
                    'description' => $row['description'],
                    'images' => $row['images'],
                    'status' => $row['status_update']
                ]);
            }
        } catch (PDOException $e) {
            error_log("Error getting submissions by customer ID: " . $e->getMessage());
        }
        
        return $submissions;
    }
    
    /**
     * Save a submission to the database
     * 
     * @param Submission $submission The submission to save
     * @return bool True if successful, false otherwise
     */
    public function saveSubmission(Submission $submission) {
        try {
            // If the submission has an ID, update it, otherwise insert a new one
            if ($submission->getId()) {
                $stmt = $this->connection->prepare("
                    UPDATE submission 
                    SET username = :username, 
                        email = :email, 
                        title = :title, 
                        category = :category, 
                        description = :description, 
                        images = :images, 
                        status_update = :status,
                        customer_id = :customer_id
                    WHERE submission_id = :id
                ");
                $stmt->bindParam(':id', $submission->getId(), PDO::PARAM_INT);
            } else {
                $stmt = $this->connection->prepare("
                    INSERT INTO submission 
                    (username, email, title, category, description, images, status_update, customer_id) 
                    VALUES 
                    (:username, :email, :title, :category, :description, :images, :status, :customer_id)
                ");
            }
            
            $username = $submission->getCustomerName();
            $email = $submission->getEmail();
            $title = $submission->getTitle();
            $category = $submission->getCategory();
            $description = $submission->getDescription();
            $images = $submission->getImages();
            $status = $submission->getStatus();
            $customerId = $submission->getCustomerId();
            
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':images', $images);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            
            // If this was an insert, get the new ID and set it on the submission
            if (!$submission->getId() && $result) {
                $submission->setId($this->connection->lastInsertId());
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error saving submission: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update submission status
     * 
     * @param int $id Submission ID
     * @param string $status New status
     * @return bool True on success, false on failure
     */
    public function updateSubmissionStatus($id, $status) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "UPDATE submission SET status_update = :status WHERE submission_id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':status', $status);
            
            return $statement->execute();
        } catch (Exception $e) {
            error_log("Error updating submission status: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Assign submission to manager
     * 
     * @param int $id Submission ID
     * @param int $managerId Manager/Employee ID
     * @return bool True on success, false on failure
     */
    public function assignSubmissionToManager($id, $managerId) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "UPDATE submission SET managedBy = :managedBy WHERE submission_id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':managedBy', $managerId, PDO::PARAM_INT);
            
            return $statement->execute();
        } catch (Exception $e) {
            error_log("Error assigning submission to manager: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete submission
     * 
     * @param int $id Submission ID
     * @return bool True on success, false on failure
     */
    public function deleteSubmission($id) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "DELETE FROM submission WHERE submission_id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $statement->execute();
        } catch (Exception $e) {
            error_log("Error deleting submission: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create Submission object from database data
     * 
     * @param array $data Submission data from database
     * @return Submission Submission object
     */
    private function createSubmissionFromData($data) {
        $submission = new Submission();
        
        // Set submission properties based on the updated Submission class fields
        if (isset($data['submission_id'])) $submission->setId($data['submission_id']);
        if (isset($data['username'])) $submission->setCustomerName($data['username']);
        if (isset($data['email'])) $submission->setEmail($data['email']);
        if (isset($data['title'])) $submission->setTitle($data['title']);
        if (isset($data['category'])) $submission->setCategory($data['category']);
        if (isset($data['description'])) $submission->setDescription($data['description']);
        
        // Handle images stored as JSON
        if (isset($data['images'])) {
            $images = json_decode($data['images'], true);
            if (is_array($images)) {
                $submission->setImages($images);
            }
        }
        
        if (isset($data['status_update'])) $submission->setStatus($data['status_update']);
        if (isset($data['managedBy'])) $submission->setManagedBy($data['managedBy']);
        if (isset($data['customer_id'])) $submission->setCustomerId($data['customer_id']);
        
        return $submission;
    }
}
