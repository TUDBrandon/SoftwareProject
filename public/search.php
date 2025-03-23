<?php
require_once '../includes/common.php';
require_once '../includes/functions.php';
require_once '../includes/classes/Product.php';
require_once '../includes/classes/ProductRepository.php';
require_once '../includes/classes/Category.php';
require_once '../includes/classes/CategoryRepository.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Establish database connection
try {
    require_once __DIR__ . '/../src/DBconnect.php';
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
}

// Get search term from query string
$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';

// Create repositories
$productRepository = new ProductRepository($connection);
$categoryRepository = new CategoryRepository($connection);

// Search for products and categories if search term is provided
$matchingProducts = [];
$matchingCategories = [];

if (!empty($searchTerm)) {
    $matchingProducts = $productRepository->searchProducts($searchTerm);
    $matchingCategories = $categoryRepository->getAllCategories();
    
    // Filter categories manually
    $matchingCategories = array_filter($matchingCategories, function($category) use ($searchTerm) {
        return stripos($category->getName(), $searchTerm) !== false;
    });
}

// Convert Product objects to arrays for display
$productArrays = $productRepository->toArrays($matchingProducts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - TechTrade</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php echo generate_navbar(); ?>

    <main>
        <h1>Search Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h1>
        
        <?php if (empty($matchingCategories) && empty($matchingProducts)): ?>
            <div class="no-results">
                <h3>No results found</h3>
                <p>Try different keywords or <a href="browse.php">browse our categories</a>.</p>
            </div>
        <?php else: ?>
            
            <?php if (!empty($matchingCategories)): ?>
                <section id="categories" class="category-section">
                    <h2>Categories</h2>
                    <div class="category-grid">
                        <?php foreach ($matchingCategories as $category): ?>
                            <div class="category-card">
                                <h3><?php echo htmlspecialchars($category->getName()); ?></h3>
                                <a href="<?php echo $category->getUrl(); ?>" class="view-button">Browse <?php echo htmlspecialchars($category->getName()); ?></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
            
            <?php if (!empty($matchingProducts)): ?>
                <section id="products" class="product-section">
                    <h2>Products (<?php echo count($matchingProducts); ?>)</h2>
                    <div class="product-grid">
                        <?php foreach ($productArrays as $product): ?>
                            <?php
                            // Fix image path - if it starts with 'public/', remove it
                            $image = $product['image'];
                            if (strpos($image, 'public/') === 0) {
                                $image = substr($image, 7); // Remove 'public/' prefix
                            }
                            ?>
                            <div class="product-card">
                                <img src="<?php echo htmlspecialchars($image); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="price">€<?php echo number_format($product['price'], 2); ?></p>
                                <p class="category">Category: <?php echo htmlspecialchars($product['category']); ?></p>
                                <a href="<?php echo htmlspecialchars($product['link']); ?>" class="buy-now">View Details</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
            
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 TechTrade. All rights reserved.</p>
    </footer>
    
    <script src="js/carousel.js"></script>
</body>
</html>
