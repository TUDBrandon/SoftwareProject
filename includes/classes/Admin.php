<?php
/**
 * Admin class
 * 
 * Represents an administrator user in the TechTrade system
 * Inherits from User class
 */
class Admin extends User {
    /**
     * Constructor
     * 
     * @param array $data Admin data
     */
    public function __construct(array $data = []) {
        // Call parent constructor to initialize base User properties
        parent::__construct($data);
    }
    
    /**
     * Convert to array (including parent properties)
     * 
     * @return array
     */
    public function toArray() {
        return parent::toArray();
    }
}
