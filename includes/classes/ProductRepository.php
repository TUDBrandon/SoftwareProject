<?php
/**
 * ProductRepository class
 * 
 * Handles database operations for products
 */
class ProductRepository {
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
     * Get all products
     * 
     * @return array Array of Product objects
     */
    public function getAllProducts() {
        $products = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM products";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $products[] = new Product($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving products: " . $e->getMessage());
        }
        
        return $products;
    }
    
    /**
     * Get products by category
     * 
     * @param string $category Category name
     * @return array Array of Product objects
     */
    public function getProductsByCategory($category) {
        $products = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            $sql = "SELECT * FROM products WHERE category = :category";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':category', $category);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $products[] = new Product($result);
            }
        } catch (Exception $e) {
            error_log("Error retrieving products by category: " . $e->getMessage());
        }
        
        return $products;
    }
    
    /**
     * Get hardware products
     * 
     * @return array Array of Product objects
     */
    public function getHardwareProducts() {
        return $this->getProductsByCategory('Hardware');
    }
    
    /**
     * Get console products
     * 
     * @return array Array of Product objects
     */
    public function getConsoleProducts() {
        return $this->getProductsByCategory('Consoles');
    }
    
    /**
     * Get phone products
     * 
     * @return array Array of Product objects
     */
    public function getPhoneProducts() {
        return $this->getProductsByCategory('Phones');
    }
    
    /**
     * Get game products
     * 
     * @return array Array of Product objects
     */
    public function getGameProducts() {
        return $this->getProductsByCategory('Games');
    }
    
    /**
     * Convert Product objects to arrays for display
     * 
     * @param array $products Array of Product objects
     * @return array Array of product arrays
     */
    public function toArrays(array $products) {
        $arrays = [];
        foreach ($products as $product) {
            $arrays[] = $product->toArray();
        }
        return $arrays;
    }
    
    /**
     * Search products by term
     * 
     * @param string $term Search term
     * @return array Array of matching Product objects
     */
    public function searchProducts($term) {
        $matchingProducts = [];
        
        try {
            if (!$this->connection) {
                throw new Exception("Database connection not available");
            }
            
            // First try to search in the database
            $sql = "SELECT * FROM products WHERE 
                    name LIKE :term OR 
                    category LIKE :term";
            $statement = $this->connection->prepare($sql);
            $searchTerm = '%' . $term . '%';
            $statement->bindParam(':term', $searchTerm);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $matchingProducts[] = new Product($result);
            }
            
            // If no results from database or if there was an error, search in memory
            if (empty($matchingProducts)) {
                // Get all products and filter in memory
                $allProducts = array_merge(
                    $this->getHardwareProducts(),
                    $this->getConsoleProducts(),
                    $this->getPhoneProducts(),
                    $this->getGameProducts()
                );
                
                foreach ($allProducts as $product) {
                    if ($product->matchesSearchTerm($term)) {
                        $matchingProducts[] = $product;
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Error searching products: " . $e->getMessage());
            
            // If there was an error, search in memory
            $allProducts = array_merge(
                $this->getHardwareProducts(),
                $this->getConsoleProducts(),
                $this->getPhoneProducts(),
                $this->getGameProducts()
            );
            
            foreach ($allProducts as $product) {
                if ($product->matchesSearchTerm($term)) {
                    $matchingProducts[] = $product;
                }
            }
        }
        
        return $matchingProducts;
    }
}
