<?php
/**
 * CategoryRepository class
 * 
 * Handles database operations for categories
 */
class CategoryRepository {
    private $connection;
    private $defaultCategories;
    
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
        
        // Initialize default categories
        $this->defaultCategories = [
            new Category(['id' => 1, 'name' => 'Hardware', 'slug' => 'hardware']),
            new Category(['id' => 2, 'name' => 'Consoles', 'slug' => 'consoles']),
            new Category(['id' => 3, 'name' => 'Phones', 'slug' => 'phones']),
            new Category(['id' => 4, 'name' => 'Games', 'slug' => 'games'])
        ];
    }
    
    /**
     * Get all categories
     * 
     * @return array Array of Category objects
     */
    public function getAllCategories() {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            // Check if categories table exists
            $tables = $this->connection->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            
            if (in_array('categories', $tables)) {
                $sql = "SELECT * FROM categories";
                $statement = $this->connection->prepare($sql);
                $statement->execute();
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                
                if (!empty($results)) {
                    $categories = [];
                    foreach ($results as $result) {
                        $categories[] = new Category($result);
                    }
                    return $categories;
                }
            }
            
            // If no categories table or no results, return default categories
            return $this->defaultCategories;
        } catch (Exception $e) {
            error_log("Error retrieving categories: " . $e->getMessage());
            return $this->defaultCategories;
        }
    }
    
    /**
     * Get category by ID
     * 
     * @param int $id Category ID
     * @return Category|null
     */
    public function getCategoryById($id) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM categories WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return new Category($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving category by ID: " . $e->getMessage());
        }
        
        // If not found in database, check default categories
        foreach ($this->defaultCategories as $category) {
            if ($category->getId() == $id) {
                return $category;
            }
        }
        
        return null;
    }
    
    /**
     * Get category by slug
     * 
     * @param string $slug Category slug
     * @return Category|null
     */
    public function getCategoryBySlug($slug) {
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM categories WHERE slug = :slug";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':slug', $slug);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return new Category($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving category by slug: " . $e->getMessage());
        }
        
        // If not found in database, check default categories
        foreach ($this->defaultCategories as $category) {
            if ($category->getSlug() == $slug) {
                return $category;
            }
        }
        
        return null;
    }
}
