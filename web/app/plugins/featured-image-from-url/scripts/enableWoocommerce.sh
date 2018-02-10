#!/bin/bash

dir='../wp-content/plugins/woocommerce/templates/single-product/'
themeDir=`cat ../wp-content/plugins/featured-image-from-url/scripts/tmp.txt`

featured_image()
{
	file=$dir'product-image.php'
	old='$image_link.*wp_get_attachment_url.*get_post_thumbnail_id.*;'
	new='$image_link=get_post_meta($post->ID,"fifu_image_url",true);\
			if($image_link=="")\
				$image_link=wp_get_attachment_url(get_post_thumbnail_id());'
	replace
}

gallery()
{
	file=$1
	old='if.*$attachment_ids.*{'
	new='if(true){'
	replace

	old='?><\/div>'
	new='\
		for($i=0;$i<10;$i++){\
			$image_link=get_post_meta($post->ID,"fifu_image_url_".$i,true);\
			if($image_link){\
				$image_caption=get_post_meta($post->ID,"fifu_image_alt_".$i,true);\
				$image_class="zoom";\
				$image=fifu_get_html($image_link,$image_caption);\
				$attachment_id=1;\
				echo\ apply_filters("woocommerce_single_product_image_thumbnail_html",\
					sprintf("<a\ href='%s'\ class='%s'\ title='%s'\ data-rel='prettyPhoto[product-gallery]'>%s<\/a>",\
					$image_link,$image_class,$image_caption,$image),$attachment_id,$post->ID,$image_class);\
			}\
		}\
		?><\/div>'
	replace
}

category()
{
	file=$themeDir'/woocommerce.php'
	old='woocommerce_content()'
	new='fifu_cat_show_image();woocommerce_content()'
	replace
}

replace()
{
	integrated=`egrep "fifu_" $file`
	if [[ ! $integrated ]]
	then
		backup
		sed -i "s/$old/$new/" $file
	fi
}

backup()
{
	cp $file $file'.bkp'
}

featured_image

gallery $dir'product-thumbnails.php' 
gallery `find $themeDir . -iname product-thumbnails.php`

category
