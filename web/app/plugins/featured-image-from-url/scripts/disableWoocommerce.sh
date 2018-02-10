#!/bin/bash

dir='../wp-content/plugins/woocommerce/templates/single-product/'
themeDir=`cat ../wp-content/plugins/featured-image-from-url/scripts/tmp.txt`

restoreProductImage()
{
	file=$1
	overwrite
}

restoreProductThumbnails()
{
	file=$1
	overwrite
}

restoreCategory()
{
	file=$themeDir'/woocommerce.php'
	overwrite
}

overwrite()
{
	mv $file'.bkp' $file
}

restore()
{
	restoreProductImage $dir'product-image.php'
	restoreProductImage `find $themeDir . -iname product-thumbnails.php`
	restoreProductThumbnails $dir'product-thumbnails.php'
	restoreProductThumbnails `find $themeDir . -iname product-thumbnails.php`
	restoreCategory
}

restore
