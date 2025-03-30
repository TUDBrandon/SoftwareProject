<?php
/**
 * User class
 * 
 * Base class for all user types in the TechTrade system
 */
class User {
    // Private properties - encapsulation
    private $id;
    private $username;
    private $email;
    private $password;
    private $firstName;
    private $lastName;
    
    /**
     * Constructor
     * 
     * @param array $data User data
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? $data['user_id'] ?? null;
        $this->username = $data['username'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->firstName = $data['first_name'] ?? $data['firstName'] ?? '';
        $this->lastName = $data['last_name'] ?? $data['lastName'] ?? '';
    }
    
    /**
     * Get user ID
     * 
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Get username
     * 
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }
    
    /**
     * Set username
     * 
     * @param string $username
     * @return self
     */
    public function setUsername(string $username) {
        $this->username = $username;
        return $this;
    }
    
    /**
     * Get email
     * 
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }
    
    /**
     * Set email
     * 
     * @param string $email
     * @return self
     */
    public function setEmail(string $email) {
        $this->email = $email;
        return $this;
    }
    
    /**
     * Get password (hashed)
     * 
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }
    
    /**
     * Set password
     * 
     * @param string $password
     * @return self
     */
    public function setPassword(string $password) {
        // Store password as hash
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }
    
    /**
     * Verify password
     * 
     * @param string $password
     * @return bool
     */
    public function verifyPassword(string $password) {
        return password_verify($password, $this->password);
    }
    
    /**
     * Get first name
     * 
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }
    
    /**
     * Set first name
     * 
     * @param string $firstName
     * @return self
     */
    public function setFirstName(string $firstName) {
        $this->firstName = $firstName;
        return $this;
    }
    
    /**
     * Get last name
     * 
     * @return string
     */
    public function getLastName() {
        return $this->lastName;
    }
    
    /**
     * Set last name
     * 
     * @param string $lastName
     * @return self
     */
    public function setLastName(string $lastName) {
        $this->lastName = $lastName;
        return $this;
    }
    
    /**
     * Get full name
     * 
     * @return string
     */
    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }
    
    /**
     * Convert to array
     * 
     * @return array
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName
        ];
    }
}
