<?php
/**
 * UserRepository class
 * 
 * Handles database operations for users, customers, employees, and admins
 */
class UserRepository {
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
     * Get all users
     * 
     * @return array Array of User objects
     */
    public function getAllUsers() {
        $users = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM users";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $users[] = $this->createUserFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving users: " . $e->getMessage());
        }
        
        return $users;
    }
    
    /**
     * Get user by ID
     * 
     * @param int $id User ID
     * @return User|null User object or null if not found
     */
    public function getUserById($id) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM users WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $this->createUserFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving user by ID: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Get user by email
     * 
     * @param string $email User email
     * @return User|null User object or null if not found
     */
    public function getUserByEmail($email) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM users WHERE email = :email";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':email', $email);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $this->createUserFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving user by email: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Get user by username
     * 
     * @param string $username Username
     * @return User|null User object or null if not found
     */
    public function getUserByUsername($username) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM users WHERE username = :username";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':username', $username);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $this->createUserFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving user by username: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Get all customers
     * 
     * @return array Array of Customer objects
     */
    public function getAllCustomers() {
        $customers = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM users WHERE role = 'customer'";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $customers[] = $this->createUserFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving customers: " . $e->getMessage());
        }
        
        return $customers;
    }
    
    /**
     * Get all employees
     * 
     * @return array Array of Employee objects
     */
    public function getAllEmployees() {
        $employees = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM users WHERE role = 'employee'";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $employees[] = $this->createUserFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving employees: " . $e->getMessage());
        }
        
        return $employees;
    }
    
    /**
     * Get all admins
     * 
     * @return array Array of Admin objects
     */
    public function getAllAdmins() {
        $admins = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM users WHERE role = 'admin'";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $admins[] = $this->createUserFromData($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving admins: " . $e->getMessage());
        }
        
        return $admins;
    }
    
    /**
     * Save user to database
     * 
     * @param User $user User object
     * @return bool True on success, false on failure
     */
    public function saveUser(User $user) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            // Determine if this is an insert or update
            if ($user->getId()) {
                // Update existing user
                $sql = "UPDATE users SET 
                        username = :username,
                        email = :email,
                        password = :password,
                        firstName = :firstName,
                        lastName = :lastName,
                        role = :role,
                        status = :status,
                        updatedAt = NOW()
                        WHERE id = :id";
                $statement = $this->connection->prepare($sql);
                $statement->bindParam(':id', $user->getId(), PDO::PARAM_INT);
            } else {
                // Insert new user
                $sql = "INSERT INTO users 
                        (username, email, password, firstName, lastName, role, status, createdAt, updatedAt) 
                        VALUES 
                        (:username, :email, :password, :firstName, :lastName, :role, :status, NOW(), NOW())";
                $statement = $this->connection->prepare($sql);
            }
            
            // Bind common parameters
            $username = $user->getUsername();
            $email = $user->getEmail();
            $password = $user->getPassword();
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
            $role = $user->getRole();
            $status = $user->getStatus();
            
            $statement->bindParam(':username', $username);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':password', $password);
            $statement->bindParam(':firstName', $firstName);
            $statement->bindParam(':lastName', $lastName);
            $statement->bindParam(':role', $role);
            $statement->bindParam(':status', $status);
            
            $success = $statement->execute();
            
            // If this was an insert, get the new ID and set it on the user
            if ($success && !$user->getId()) {
                $newId = $this->connection->lastInsertId();
                $user->setId($newId);
            }
            
            return $success;
        } catch (Exception $e) {
            error_log("Error saving user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user status
     * 
     * @param int $id User ID
     * @param string $status New status
     * @return bool True on success, false on failure
     */
    public function updateUserStatus($id, $status) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "UPDATE users SET status = :status, updatedAt = NOW() WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':status', $status);
            
            return $statement->execute();
        } catch (Exception $e) {
            error_log("Error updating user status: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete user
     * 
     * @param int $id User ID
     * @return bool True on success, false on failure
     */
    public function deleteUser($id) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "DELETE FROM users WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $statement->execute();
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Authenticate user
     * 
     * @param string $username Username or email
     * @param string $password Password
     * @return User|null User object if authentication successful, null otherwise
     */
    public function authenticateUser($username, $password) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            // Check if username is an email
            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $user = $this->getUserByEmail($username);
            } else {
                $user = $this->getUserByUsername($username);
            }
            
            // Verify password if user exists
            if ($user && password_verify($password, $user->getPassword())) {
                return $user;
            }
        } catch (Exception $e) {
            error_log("Error authenticating user: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Create appropriate User object from database data
     * 
     * @param array $data User data from database
     * @return User|Customer|Employee|Admin User object of appropriate type
     */
    private function createUserFromData($data) {
        // Create the appropriate user type based on role
        switch ($data['role']) {
            case 'customer':
                $user = new Customer();
                break;
            case 'employee':
                $user = new Employee();
                break;
            case 'admin':
                $user = new Admin();
                break;
            default:
                $user = new User();
        }
        
        // Set common user properties
        $user->setId($data['id']);
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setRole($data['role']);
        $user->setStatus($data['status']);
        $user->setCreatedAt($data['createdAt']);
        $user->setUpdatedAt($data['updatedAt']);
        
        return $user;
    }
}
