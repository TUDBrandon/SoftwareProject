<?php
function create_form_field($name, $label, $type = 'text', $required = true, $value = '') {
    $required_attr = $required ? 'required' : '';
    $html = <<<HTML
    <div class="form-group">
        <label for="$name">$label</label>
        <input type="$type" name="$name" id="$name" value="$value" $required_attr>
    </div>
    HTML;
    
    return $html;
}

function display_product_carousel($products, $category_id) {
    $html = '<div class="carousel-container" id="' . $category_id . '-carousel">';
    $html .= '<div class="carousel-track">';
    
    foreach ($products as $product) {
        // Fix image path - if it starts with 'public/', remove it
        $image = $product['image'];
        if (strpos($image, 'public/') === 0) {
            $image = substr($image, 7); // Remove 'public/' prefix
        }
        
        $html .= '<div class="carousel-item">';
        $html .= '<a href="' . $product['link'] . '" class="product-link">';
        $html .= '<div class="product-card">';
        $html .= '<img src="' . $image . '" alt="' . $product['name'] . '">';
        $html .= '<h3>' . $product['name'] . '</h3>';
        $html .= '<p class="price">$' . number_format($product['price'], 2) . '</p>';
        $html .= '<button class="buy-now">View Details</button>';
        $html .= '</div>';
        $html .= '</a>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    $html .= '<button class="carousel-prev" aria-label="Previous">&#10094;</button>';
    $html .= '<button class="carousel-next" aria-label="Next">&#10095;</button>';
    $html .= '</div>';
    
    return $html;
}

function get_hardware_products() {
    try {
        require_once __DIR__ . '/../src/DBconnect.php';
        global $connection;
        
        if (!$connection) {
            throw new Exception("Database connection failed");
        }
        
        $sql = "SELECT * FROM products WHERE category = 'Hardware'";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert database column names to match the expected format
        $formatted_results = [];
        foreach ($result as $product) {
            // Fix image path - if it starts with 'public/', remove it
            $image = $product['image'] ?? $product['image_path'] ?? '';
            if (strpos($image, 'public/') === 0) {
                $image = substr($image, 7); // Remove 'public/' prefix
            }
            
            $formatted_results[] = [
                'name' => $product['name'] ?? $product['product_name'] ?? '',
                'price' => $product['price'] ?? 0,
                'image' => $image,
                'category' => $product['category'] ?? '',
                'link' => $product['link'] ?? $product['product_link'] ?? '#'
            ];
        }
        
        return $formatted_results;
    } catch (Exception $e) {
        error_log("Error retrieving hardware products: " . $e->getMessage());
        return [];
    }
}

function get_console_products() {
    try {
        require_once __DIR__ . '/../src/DBconnect.php';
        global $connection;
        
        if (!$connection) {
            throw new Exception("Database connection failed");
        }
        
        $sql = "SELECT * FROM products WHERE category = 'Consoles'";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert database column names to match the expected format
        $formatted_results = [];
        foreach ($result as $product) {
            // Fix image path - if it starts with 'public/', remove it
            $image = $product['image'] ?? $product['image_path'] ?? '';
            if (strpos($image, 'public/') === 0) {
                $image = substr($image, 7); // Remove 'public/' prefix
            }
            
            $formatted_results[] = [
                'name' => $product['name'] ?? $product['product_name'] ?? '',
                'price' => $product['price'] ?? 0,
                'image' => $image,
                'category' => $product['category'] ?? '',
                'link' => $product['link'] ?? $product['product_link'] ?? '#'
            ];
        }
        
        return $formatted_results;
    } catch (Exception $e) {
        error_log("Error retrieving console products: " . $e->getMessage());
        return [];
    }
}

function get_phone_products() {
    try {
        require_once __DIR__ . '/../src/DBconnect.php';
        global $connection;
        
        if (!$connection) {
            throw new Exception("Database connection failed");
        }
        
        $sql = "SELECT * FROM products WHERE category = 'Phones'";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert database column names to match the expected format
        $formatted_results = [];
        foreach ($result as $product) {
            // Fix image path - if it starts with 'public/', remove it
            $image = $product['image'] ?? $product['image_path'] ?? '';
            if (strpos($image, 'public/') === 0) {
                $image = substr($image, 7); // Remove 'public/' prefix
            }
            
            $formatted_results[] = [
                'name' => $product['name'] ?? $product['product_name'] ?? '',
                'price' => $product['price'] ?? 0,
                'image' => $image,
                'category' => $product['category'] ?? '',
                'link' => $product['link'] ?? $product['product_link'] ?? '#'
            ];
        }
        
        return $formatted_results;
    } catch (Exception $e) {
        error_log("Error retrieving phone products: " . $e->getMessage());
        return [];
    }
}

function get_game_products() {
    try {
        require_once __DIR__ . '/../src/DBconnect.php';
        global $connection;
        
        if (!$connection) {
            throw new Exception("Database connection failed");
        }
        
        $sql = "SELECT * FROM products WHERE category = 'Games'";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert database column names to match the expected format
        $formatted_results = [];
        foreach ($result as $product) {
            // Fix image path - if it starts with 'public/', remove it
            $image = $product['image'] ?? $product['image_path'] ?? '';
            if (strpos($image, 'public/') === 0) {
                $image = substr($image, 7); // Remove 'public/' prefix
            }
            
            $formatted_results[] = [
                'name' => $product['name'] ?? $product['product_name'] ?? '',
                'price' => $product['price'] ?? 0,
                'image' => $image,
                'category' => $product['category'] ?? '',
                'link' => $product['link'] ?? $product['product_link'] ?? '#'
            ];
        }
        
        return $formatted_results;
    } catch (Exception $e) {
        error_log("Error retrieving game products: " . $e->getMessage());
        return [];
    }
}

function include_carousel_js() {
    $timestamp = time();
    echo '<script src="../includes/js/carousel.js?v=' . $timestamp . '"></script>';
}

/**
 * Get bestselling products
 * 
 * @param int $limit Number of products to return
 * @return array Array of bestselling products
 */
function get_bestselling_products($limit = 5) {
    try {
        require_once __DIR__ . '/../src/DBconnect.php';
        global $connection;
        
        if (!$connection) {
            throw new Exception("Database connection failed");
        }
        
        // In a real system, you might have a sales or views count to determine bestsellers
        // For now, we'll just get a mix of products from different categories
        $sql = "SELECT * FROM products 
                ORDER BY RAND() 
                LIMIT :limit";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert database column names to match the expected format
        $formatted_results = [];
        foreach ($result as $product) {
            // Fix image path - if it starts with 'public/', remove it
            $image = $product['image'] ?? $product['image_path'] ?? '';
            if (strpos($image, 'public/') === 0) {
                $image = substr($image, 7); // Remove 'public/' prefix
            }
            
            $formatted_results[] = [
                'name' => $product['name'] ?? $product['product_name'] ?? '',
                'price' => $product['price'] ?? 0,
                'image' => $image,
                'category' => $product['category'] ?? '',
                'link' => $product['link'] ?? $product['product_link'] ?? '#'
            ];
        }
        
        return $formatted_results;
    } catch (Exception $e) {
        error_log("Error retrieving bestselling products: " . $e->getMessage());
        return [];
    }
}

?>
