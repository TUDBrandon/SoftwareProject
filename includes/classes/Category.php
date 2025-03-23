<?php
/**
 * Category class
 * 
 * Represents a product category in the TechTrade system
 */
class Category {
    // Private properties - encapsulation
    private $id;
    private $name;
    private $slug;
    private $icon;
    
    /**
     * Constructor
     * 
     * @param array $data Category data
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->slug = $data['slug'] ?? $this->generateSlug($this->name);
        $this->icon = $data['icon'] ?? '';
    }
    
    /**
     * Get category ID
     * 
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Set category ID
     * 
     * @param int $id
     * @return self
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    /**
     * Get category name
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set category name
     * 
     * @param string $name
     * @return self
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Get category slug
     * 
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }
    
    /**
     * Set category slug
     * 
     * @param string $slug
     * @return self
     */
    public function setSlug($slug) {
        $this->slug = $slug;
        return $this;
    }
    
    /**
     * Get category icon
     * 
     * @return string
     */
    public function getIcon() {
        return $this->icon;
    }
    
    /**
     * Set category icon
     * 
     * @param string $icon
     * @return self
     */
    public function setIcon($icon) {
        $this->icon = $icon;
        return $this;
    }
    
    /**
     * Generate slug from name
     * 
     * @param string $name
     * @return string
     */
    private function generateSlug($name) {
        // Convert to lowercase and replace spaces with hyphens
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
    
    /**
     * Convert to array
     * 
     * @return array
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon
        ];
    }
    
    /**
     * Get URL for category
     * 
     * @return string
     */
    public function getUrl() {
        return "browse.php?category=" . strtolower($this->slug);
    }
}
