<?php
function init_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function create_form_field($name, $label, $type = 'text', $required = true, $value = '') {
    $html = '<div class="form-group">';
    $html .= '<label for="' . $name . '">' . $label . '</label>';
    
    if ($type === 'textarea') {
        $html .= '<textarea name="' . $name . '" id="' . $name . '"';
        $html .= $required ? ' required' : '';
        $html .= '>' . htmlspecialchars($value) . '</textarea>';
    } else {
        $html .= '<input type="' . $type . '" name="' . $name . '" id="' . $name . '"';
        if ($type !== 'file') {
            $html .= ' value="' . htmlspecialchars($value) . '"';
        }
        $html .= $required ? ' required' : '';
        $html .= '>';
    }
    
    $html .= '</div>';
    return $html;
}

function display_product_carousel($products, $category_id) {
    $html = '<div class="carousel-container">';
    $html .= '<div class="carousel" id="' . $category_id . '-carousel">';
    
    foreach($products as $product) {
        $html .= '<div class="product-card">';
        $html .= '<a href="' . $product['link'] . '" class="product-link">';
        $html .= '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
        $html .= '<h3>' . $product['name'] . '</h3>';
        $html .= '<p class="category">' . $product['category'] . '</p>';
        $html .= '<p class="price">€' . $product['price'] . '</p>';
        $html .= '</a>';
        $html .= '<button class="buy-now">Buy Now</button>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    $html .= '<div class="carousel-controls">';
    $html .= '<button class="prev" data-carousel="' . $category_id . '-carousel">Previous</button>';
    $html .= '<button class="next" data-carousel="' . $category_id . '-carousel">Next</button>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

function get_hardware_products() {
    return [
        [
            'name' => 'MSI RX570 8GB',
            'price' => 120,
            'image' => 'images/msi370.jpg',
            'category' => 'Hardware',
            'link' => 'msi-rx570.php'
        ],
        [
            'name' => 'Intel Core i7 13th Gen',
            'price' => 399.99,
            'image' => 'images/CPUi713th.jpg',
            'category' => 'Hardware',
            'link' => 'CPUi713th.php'
        ],
        [
            'name' => 'NVIDIA RTX 4090',
            'price' => 1599,
            'image' => 'images/rtx4090.jpg',
            'category' => 'Hardware',
            'link' => 'rtx4090.php'
        ],
        [
            'name' => 'NVIDIA RTX 5090',
            'price' => 1999,
            'image' => 'images/rtx5090.jpg',
            'category' => 'Hardware',
            'link' => 'rtx5090.php'
        ],
        [
            'name' => 'Intel Core i9',
            'price' => 549.99,
            'image' => 'images/inteli9.jpg',
            'category' => 'Hardware',
            'link' => 'intel-i9.php'
        ]
    ];
}

function get_console_products() {
    return [
        [
            'name' => 'PlayStation 5',
            'price' => 499,
            'image' => 'images/ps5.jpg',
            'category' => 'Consoles',
            'link' => 'ps5.php'
        ],
        [
            'name' => 'PlayStation 4',
            'price' => 299.99,
            'image' => 'images/ps4.jpg',
            'category' => 'Consoles',
            'link' => 'ps4.php'
        ],
        [
            'name' => 'Nintendo Switch',
            'price' => 299,
            'image' => 'images/switch.jpg',
            'category' => 'Consoles',
            'link' => 'nintendo-switch.php'
        ],
        [
            'name' => 'Xbox Series X',
            'price' => 499.99,
            'image' => 'images/xboxX.png',
            'category' => 'Consoles',
            'link' => 'xbox-series-x.php'
        ],
        [
            'name' => 'Meta Quest 2',
            'price' => 299.99,
            'image' => 'images/oculus2.jpg',
            'category' => 'Consoles',
            'link' => 'oculus-quest2.php'
        ]
    ];
}

function get_phone_products() {
    return [
        [
            'name' => 'iPhone 14',
            'price' => 799,
            'image' => 'images/iphone14.jpg',
            'category' => 'Phones',
            'link' => 'iphone14.php'
        ],
        [
            'name' => 'iPhone 14 - Black',
            'price' => 799,
            'image' => 'images/black_iphone14.jpg',
            'category' => 'Phones',
            'link' => 'black-iphone14.php'
        ],
        [
            'name' => 'iPhone 15 - Green',
            'price' => 899,
            'image' => 'images/green_iphone15.jpg',
            'category' => 'Phones',
            'link' => 'green-iphone15.php'
        ],
        [
            'name' => 'iPhone 15 Pro Max',
            'price' => 1199,
            'image' => 'images/iphone15proMax.jpg',
            'category' => 'Phones',
            'link' => 'iphone15-promax.php'
        ],
        [
            'name' => 'Samsung Galaxy S23 Ultra',
            'price' => 1199,
            'image' => 'images/samsungGalazyS23Ultra.jpg',
            'category' => 'Phones',
            'link' => 'samsung-galaxy-s23-ultra.php'
        ]
    ];
}

function get_game_products() {
    return [
        [
            'name' => 'Call of Duty: Black Ops 6',
            'price' => 69.99,
            'image' => 'images/CodBO6.jpg',
            'category' => 'Games',
            'link' => 'codbo6.php'
        ],
        [
            'name' => 'NBA 2K25 - PS5',
            'price' => 69.99,
            'image' => 'images/2k25PS5.jpg',
            'category' => 'Games',
            'link' => '2k25PS5.php'
        ],
        [
            'name' => 'Grand Theft Auto V - Xbox',
            'price' => 29.99,
            'image' => 'images/GTAVXbox.jpg',
            'category' => 'Games',
            'link' => 'GTAVXbox.php'
        ],
        [
            'name' => 'Hogwarts Legacy - Xbox',
            'price' => 59.99,
            'image' => 'images/hogwartslegacyXbox.jpg',
            'category' => 'Games',
            'link' => 'hogwarts-legacy-xbox.php'
        ],
        [
            'name' => 'Spider-Man 2 - PS5',
            'price' => 69.99,
            'image' => 'images/spiderman2PS5.png',
            'category' => 'Games',
            'link' => 'spiderman2-ps5.php'
        ],
        [
            'name' => 'Tekken 8 - PS5',
            'price' => 69.99,
            'image' => 'images/tekken8PS5.jpg',
            'category' => 'Games',
            'link' => 'tekken8-ps5.php'
        ],
        [
            'name' => 'Dogman - Switch',
            'price' => 39.99,
            'image' => 'images/dogmanSwitch.jpg',
            'category' => 'Games',
            'link' => 'dogman-switch.php'
        ],
        [
            'name' => 'Instant Sports - Switch',
            'price' => 29.99,
            'image' => 'images/instantsportsSwitch.jpg',
            'category' => 'Games',
            'link' => 'instant-sports-switch.php'
        ],
        [
            'name' => 'It Takes Two - Switch',
            'price' => 39.99,
            'image' => 'images/ittakes2Switch.jpg',
            'category' => 'Games',
            'link' => 'it-takes-two-switch.php'
        ],
        [
            'name' => 'Steam Gift Card',
            'price' => 50.00,
            'image' => 'images/gitcardSteam.jpeg',
            'category' => 'Games',
            'link' => 'steam-giftcard.php'
        ]
    ];
}

function include_carousel_js() {
    $js = <<<EOT
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle category scrolling
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category');
    
    if (category) {
        const categorySection = document.getElementById(category);
        if (categorySection) {
            setTimeout(function() {
                categorySection.scrollIntoView({ behavior: 'smooth' });
            }, 100);
        }
    }
    
    // Add click handlers to all carousel buttons
    document.querySelectorAll('.carousel-controls .prev').forEach(function(button) {
        button.addEventListener('click', function() {
            const carouselId = this.getAttribute('data-carousel');
            const carousel = document.getElementById(carouselId);
            carousel.scrollBy({
                left: -266,
                behavior: 'smooth'
            });
        });
    });
    
    document.querySelectorAll('.carousel-controls .next').forEach(function(button) {
        button.addEventListener('click', function() {
            const carouselId = this.getAttribute('data-carousel');
            const carousel = document.getElementById(carouselId);
            carousel.scrollBy({
                left: 266,
                behavior: 'smooth'
            });
        });
    });
});
</script>
EOT;
    return $js;
}
?>
