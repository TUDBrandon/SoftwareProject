<?php

use TestCase;

class ProductTest extends TestCase {
    public function testProductBelongsToCategory() {
        $category1 = new Category(1, 'Hardware');
        $category2 = new Category(2, 'Consoles')
        $category3 = new Category(3, 'Phones');
        $category4 = new Category(4, 'Games');

        $product1 = new Product(1, 'Graphics Card RTX 4090', 1599.99);

        $this->assertTrue($product1->belongsToCategory($category1));
        $this->assertFalse($product1->belongsToCategory($category2));
        $this->assertFalse($product1->belongsToCategory($category3));
        $this->assertFalse($product1->belongsToCategory($category4));
    }
}

?>