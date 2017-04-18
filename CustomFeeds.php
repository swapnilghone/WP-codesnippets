<?php

/*
 * Create custom feeds for any custom post type 
 * in below example we crete feeds for products which can be access via feed/product
 */

/*
 * Add following code in function.php
 */

add_action('init', 'custom_rewrite_basic', 10);

function custom_rewrite_basic() {
    global $wp, $wp_rewrite;
    $f = explode('/', $_SERVER['REQUEST_URI']);
    $wp->add_query_var('feed-products');
    $wp_rewrite->add_rule('feed/products/', 'index.php?feed=products');
    $wp_rewrite->flush_rules(false);
}

/**
 * Register custom RSS template.
 */
add_action('after_setup_theme', 'prodcut_feed_template');

function prodcut_feed_template() {
    add_feed('products', 'product_custom_feed_render');
}

/**
 * Custom RSS template callback.
 */
function product_custom_feed_render() {
    get_template_part('feed', 'products');
}

/*
 * add following code in feed-products.php it fetch the data and display it in xml format 
 * 
 */

header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );
$product_categories = get_categories( array('taxonomy'=>'product_cat','hide_empty'=>false) );
$products = get_posts(array('post_type'=>'product','post_status'=>'publish','posts_per_page'=>-1));
/**
 * Start RSS feed.
 */
echo '<?xml version="1.0" encoding="UTF-8" ?> '; ?>
<Feed xmlns="http://www.bazaarvoice.com/xs/PRR/ProductFeed/5.6" name="kidCo" incremental="false" extractDate="<?php echo date('Y-m-d\Th:i:s',time());?>">
    <Categories>
        <?php
         foreach ($product_categories as $pro_cat){
             
            $thumbnail_id = get_woocommerce_term_meta( $pro_cat->term_id, 'thumbnail_id', true ); 
            // get the image URL
            $image_url = wp_get_attachment_url( $thumbnail_id ); 
            $image = '';
            if($image_url){
                $image = '<ImageUrl>'.$image.'</ImageUrl>';
            }
            
             echo '<Category>
                    <ExternalId>'.$pro_cat->term_id.'</ExternalId>
                    <Name>'.$pro_cat->name.'</Name>
                    <CategoryPageUrl>'.get_term_link($pro_cat->slug,'product_cat').'</CategoryPageUrl>
                    '.$image.'
                    </Category>';
         }
        ?>
    </Categories>
    <Products>
        <?php
                foreach ($products as $pro){
                    $meta = get_post_meta($pro->ID );
                    $product = wc_get_product( $pro->ID );
                    $product_cats = wp_get_post_terms($pro->ID,'product_cat');

                    $CategoryExternalId = '';
                    if ( $product_cats && ! is_wp_error ( $product_cats ) ){
                         $CategoryExternalId = '<CategoryExternalId>'.$product_cats[0]->term_id.'</CategoryExternalId>';
                    }
                    
                    if($product->is_type('variable')){
                        
                       $ImageUrl = '';
                       $upc = get_post_meta($pro->ID,'_upc_code',true);
                       if(has_post_thumbnail($pro->ID)){
                            $url = wp_get_attachment_url( get_post_thumbnail_id($pro->ID) );
                            $ImageUrl = '<ImageUrl>'.$url.'</ImageUrl>';
                       }
                        
                       if($upc!=''){
                            $upc = '<UPCs><UPC>'.$upc.'</UPC></UPCs>';
                       }
                        echo '<Product>
                            <ExternalId>'.$pro->ID.'</ExternalId>
                            <Name>'.htmlentities($pro->post_name).'</Name>
                            '.$CategoryExternalId.' 
                            <ProductPageUrl>'.get_permalink($pro->ID).'</ProductPageUrl>
                            '.$ImageUrl.'';
                        
                        if($pro->post_excerpt!=''){
                            echo '<Description>'.wsi_clean($pro->post_excerpt).'</Description>';
                        }elseif($pro->post_content!=''){
                            echo '<Description>'.wsi_clean($pro->post_content).'</Description>';
                        }  
                        echo '<Attributes>
                                    <Attribute id="BV_FE_FAMILY">
                                        <Value>'.htmlentities($pro->post_name).'</Value>
                                    </Attribute>
                                    <Attribute id="BV_FE_EXPAND">
                                        <Value>BV_FE_FAMILY:'.htmlentities($pro->post_name).'</Value>
                                    </Attribute>
                                </Attributes>';
                        
                        
                         // a variable product
                         $available_variations = $product->get_available_variations();
                         $variation_tags = '';
                         if(is_array($available_variations) && !empty($available_variations)){
                             $upc = '<UPCs>';
                            foreach ($available_variations as $av){

                               $ImageUrl = '';

                               $attributes = '<Attribute id="BV_FE_FAMILY">
                                           <Value>'.htmlentities($pro->post_name).'</Value>
                                       </Attribute>';

                               foreach ($av['attributes'] as $family=>$value){ 

                                       $attrbute_name = $value;

                                       $attributes .= '
                                      <Attribute id="BV_FE_FAMILY">
                                           <Value>'.htmlentities($value).'</Value>
                                       </Attribute>
                                       <Attribute id="BV_FE_EXPAND">
                                           <Value>BV_FE_FAMILY:'.$pro->post_name.'</Value>
                                       </Attribute> ';
                               }

                               if($av['image_src']!=''){
                                   $ImageUrl = '<ImageUrl>'.$av['image_src'].'</ImageUrl>';
                               }

                               $variation_tags .= '<Product>
                                   <ExternalId>'.$av['variation_id'].'</ExternalId>
                                   <Name>'.htmlentities($pro->post_name.'-'.$attrbute_name).'</Name>
                                   '.$CategoryExternalId.' 
                                   <ProductPageUrl>'.get_permalink($pro->ID).'</ProductPageUrl>
                                   '.$ImageUrl.'';

                               if($av['variation_description']!=''){
                                   $variation_tags .= '<Description>'.wsi_clean($av['variation_description']).'</Description>';
                               }elseif($pro->post_excerpt!=''){
                                   $variation_tags .= '<Description>'.wsi_clean($pro->post_excerpt).'</Description>';
                               } 
                               $upc .= '<UPC>'.get_post_meta($av['variation_id'],'_variation_upc_code',true).'</UPC>';
                               $variation_tags .= '<Attributes>'.$attributes.'</Attributes>';
                                $variation_tags .= '<UPCs><UPC>'.get_post_meta($av['variation_id'],'_variation_upc_code',true).'</UPC></UPCs>';
                               $variation_tags .= '</Product>';
                            }
                            $upc .= '</UPCs>';
                         }
                        echo $upc;
                        echo '</Product>';
                        echo $variation_tags;
                   }else{ 
                       
                       $ImageUrl = '';
                       $upc = get_post_meta($pro->ID,'_upc_code',true);
                       
                       if(has_post_thumbnail($pro->ID)){
                            $url = wp_get_attachment_url( get_post_thumbnail_id($pro->ID) );
                            $ImageUrl = '<ImageUrl>'.$url.'</ImageUrl>';
                       }
                        
                       if($upc!=''){
                            $upc = '<UPCs><UPC>'.$upc.'</UPC></UPCs>';
                       }
                       
                        echo '<Product>
                                <ExternalId>'.$pro->ID.'</ExternalId>
                                <Name>'.$pro->post_name.'</Name>
                                '.$CategoryExternalId.' 
                                <ProductPageUrl>'.get_permalink($pro->ID).'</ProductPageUrl>
                                '.$ImageUrl.'';
                        if($pro->post_excerpt!=''){
                            echo '<Description>'.wsi_clean($pro->post_excerpt).'</Description>';
                        }elseif($pro->post_content!=''){
                            echo '<Description>'.wsi_clean($pro->post_content).'</Description>';
                        }  
                        echo $upc;
                        echo '</Product>';
                   }
                       
                }
        ?>
    </Products>
</Feed>
