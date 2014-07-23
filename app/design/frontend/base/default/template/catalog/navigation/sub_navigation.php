<?php
 
/* File Location
 
* /new/app/design/frontend/base/default/template/catalog/navigation
 
* Compatible with: Magento Community 1.7.2.0
 
*/
 
?>
 
<div id="categories">
 
<div class="col_full">
 
<div class="listing" >
 
<?php
 
function getImageUrl($category)
 
{
 
$cur_category=Mage::getModel('catalog/category')->load($category->getId());
 
$layer = Mage::getSingleton('catalog/layer');
 
$layer->setCurrentCategory($cur_category);
 
$url = $this->getCurrentCategory()->getImageUrl();
 
return $url;
 
};
 
 
//Get the Current Category
 
$_maincategorylisting=$this->getCurrentCategory();
 
 
// Iterate all categories in store
 
foreach ($this->getCurrentChildCategories() as $_category):
 
 
// If category is Active
 
if($_category->getIsActive()):
 
 
// Load the actual category object for this category
 
$cur_category = Mage::getModel('catalog/category')->load($_category->getId());
 
 
// Load a random product from this category
 
$products = Mage::getResourceModel('catalog/product_collection')->addCategoryFilter($cur_category);
 
$products->getSelect()->order(new Zend_Db_Expr('RAND()'))->limit(100);
 
 
$products->load();
 
 
// This a bit of a fudge - there's only one element in the collection
 
$_product = null;
 
 
foreach ( $products as $_product ) {}
 
 
if(isset($_product)):
 
?>
 
 
<div class="sub-category-listing" style="float: left; margin-left: 20px; margin-right: 10px; padding: 0px; text-align: center; width: 155px; ">
 
<div class="linkimage"><p><a href="<?php echo $this->getCategoryUrl($_category)?>" class="product-image">
 
 
<?php
 
$layer = Mage::getSingleton('catalog/layer');
 
$layer->setCurrentCategory($cur_category);
 
?>
 
 
<?
 
// If there is an image set for the category - Display it
 
if($_imgUrl=$this->getCurrentCategory()->getImageUrl()):?>
 
<img src="<?php echo $_imgUrl ?>" style="padding: 10px;" alt="<?php echo $this->htmlEscape($_product->getName()) ?>" />
 
<?php endif; ?>
 
 
<?
 
// If there is not a image set for that Category - Display a random product Image
 
if(!$_imgUrl):
 
 
// Let's load the category Model and grab the product collection of that category
 
 
$product_collection = Mage::getModel('catalog/category')->load($_category->getId())->getProductCollection();
 
$product_collection->getSelect()->order(new Zend_Db_Expr('RAND()'))->limit(1);
 
 
// Now let's loop through the product collection and print the ID of every product
 
foreach($product_collection as $product) {
 
 
// Get the product ID
 
$product_id = $product->getId();
 
 
// Load the full product model based on the product ID
 
 
$full_product = Mage::getModel('catalog/product')->load($product_id);
 
 
// Now that we loaded the full product model, let's access all of it's data
 
 
// Let's get the Product Name
 
 
$product_name = $full_product->getName();
 
 
// Let's get the Product URL path
 
 
$product_url = $full_product->getProductUrl();
 
 
// Let's get the Product Image URL
 
 
$product_image_url = $full_product->getImageUrl();
 
 
// Let's print the product information we gathered and continue onto the next one
 
} //End For Each
 
?>
 
<img src="<?php echo $product_image_url; ?>" width="135" height="135" style="padding: 10px;" alt="<?php echo $this->htmlEscape($_product->getName()) ?>" />
 
<?php endif; ?>
 
</a>
 
</div>
 
<a href="<?php echo $this->getCategoryUrl($_category)?>">
 
<h2 class="product-name" style="margin-top: 10px;"><?php echo $_category->getName()?></a></h2>
 
<? if($_description=$this->getCurrentCategory()->getDescription()):?>
 
<p class="category-description">
 
<?php echo $_description ?></p>
 
<?php endif; ?>
 
</div>
 
<?php
 
endif;
 
endif;
 
endforeach;
 
?>
 
</div>
 
<br clear=all>
 
</div>
 
</div>