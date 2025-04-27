<?php
/**
 * Product class
 * 
 * Encapsulates product data and provides consistent image handling
 */
class Product {
    // Private properties - encapsulation
    private $id;
    private $name;
    private $price;
    private $image;
    private $categoryId;
    private $link;
    
    /**
     * Constructor
     * 
     * @param array $data Product data from database or other source
     */
    public function __construct(array $data = []) {
        // Initialize properties from data array with fallbacks
        $this->id = $data['id'] ?? $data['product_id'] ?? null;
        $this->name = $data['name'] ?? $data['product_name'] ?? '';
        $this->price = $data['price'] ?? 0;
        $this->category = $data['category'] ?? '';
        $this->link = $data['link'] ?? $data['product_link'] ?? '#';
        
        // Handle image path consistently
        $this->setImage($data['image'] ?? $data['image_path'] ?? '');
    }

    public function belongsToCategory(Category $category): bool {
        return $this->categoryId === $category->getId();
    }

    public function getCategoryId(): int {
        return $this->categoryId;
    }
    
    /**
     * Get product ID
     * 
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Get product name
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set product name
     * 
     * @param string $name
     * @return self
     */
    public function setName(string $name) {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Get product price
     * 
     * @return float
     */
    public function getPrice() {
        return $this->price;
    }
    
    /**
     * Set product price
     * 
     * @param float $price
     * @return self
     */
    public function setPrice(float $price) {
        // Ensure price is not negative
        $this->price = max(0, $price);
        return $this;
    }
    
    /**
     * Get product image path
     * 
     * @return string
     */
    public function getImage() {
        return $this->image;
    }
    
    /**
     * Set product image path
     * 
     * @param string $image
     * @return self
     */
    public function setImage(string $image) {
        // Consistent image handling - remove 'public/' prefix if present
        if (strpos($image, 'public/') === 0) {
            $image = substr($image, 7);
        }
        
        $this->image = $image;
        return $this;
    }
    
    /**
     * Get product category
     * 
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }
    
    /**
     * Set product category
     * 
     * @param string $category
     * @return self
     */
    public function setCategory(string $category) {
        $this->category = $category;
        return $this;
    }
    
    /**
     * Get product link
     * 
     * @return string
     */
    public function getLink() {
        return $this->link;
    }
    
    /**
     * Set product link
     * 
     * @param string $link
     * @return self
     */
    public function setLink(string $link) {
        $this->link = $link;
        return $this;
    }
    
    /**
     * Convert product to array for display
     * 
     * @return array
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'image' => $this->image,
            'category' => $this->category,
            'link' => $this->link
        ];
    }
    
    /**
     * Format price for display
     * 
     * @param string $currencySymbol
     * @return string
     */
    public function getFormattedPrice(string $currencySymbol = '€') {
        return $currencySymbol . number_format($this->price, 2);
    }
    
    /**
     * Check if image file exists
     * 
     * @return bool
     */
    public function imageExists() {
        $publicPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->image;
        return file_exists($publicPath);
    }
    
    /**
     * Check if product matches search term
     * 
     * @param string $term Search term
     * @return bool
     */
    public function matchesSearchTerm($term) {
        $term = strtolower($term);
        return strpos(strtolower($this->name), $term) !== false || 
               strpos(strtolower($this->category), $term) !== false;
    }
}
