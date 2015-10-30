<?php

/* 
 * Add new setting tab to woocommerce admin to set the discount
 */


add_filter( 'woocommerce_settings_tabs_array', 'custom_discount_tab',25 );

function custom_discount_tab($settings_tabs){
    
    $settings_tabs['discount'] = __('Discount','woocommerce-discount-tab'); // or whatever you fancy
    return $settings_tabs;
    
}

add_filter( 'woocommerce_settings_tabs_discount', 'settings_tab' );

function settings_tab(){
    $settings = array_values(get_option('discount_settings'));
    $cnt = 1;
    $new_cnt = 1;
    
    // For Delete
    if($_GET['del_cnt']){
        $temp1 = $_GET['del_cnt']-1;
        unset($settings[$temp1]);
        update_option('discount_settings',$settings);
        echo '<div id="message" class="updated fade"><p><strong>Rule Deleted sucessfully.</strong></p></div>';
        ?>
                <script>
                    jQuery(function(){
                        console.log('hi');
                        window.location.replace("<?php echo admin_url('admin.php?page=wc-settings&tab=discount'); ?>");
                    });
                </script>
        <?php
    }
        
    // For Edit
    if($_GET['cnt']){
        foreach ($settings as $ss){
            if($_GET['cnt']==$new_cnt){
                $cqty = $ss['custom_cart_discount'];
                $drate = $ss['custom_discount_rate'];
            }
            $new_cnt++;
        }
    }
    ?>
        <h3>Discount Rule</h3>  
        <table class="widefat fixed posts">
            <thead>
            <tr>
                <td>Cart Quantity</td>
                <td>Discount Rate</td>
                <td>Actions</td>
            </tr>
            </thead>
            <tbody>
            <?php
            if($settings){
            foreach ($settings as $ss){ ?>
                <tr class="alternate">
                    <td><?php echo $ss['custom_cart_discount']; ?></td>
                    <td><?php echo $ss['custom_discount_rate']; ?> %</td>
                    <td class="order_actions column-order_actions">
                        <a href="<?php echo admin_url('admin.php?page=wc-settings&tab=discount&cnt='.$cnt.''); ?>" class="button Edit" >Edit</a>
                        <a href="javascript:void(0)" class="button Delete" onclick="del_rule(<?php echo $cnt; ?>)">Delete</a>
                    </td>
                </tr>
            <?php $cnt++; } }else{  ?>
                <tr class="alternate">
                    <td colspan="4">(No Rule define yet)</td>
                </tr>
            <?php } ?> 
            </tbody>
            <tfoot>
                <td>Cart Quantity</td>
                <td>Discount Rate</td>
                <td>Actions</td>
            </tr>
            </tfoot>
        </table>
        <br/><hr/><br/>
            <h3>Add New Rule</h3>
        <table class="widefat fixed posts">
            <tr>
                <td>Cart Quantity</td>
                <td><input type="text" name="cart_discount" value="<?php echo $cqty; ?>"></td>
                <td>Discount Rate (in percent)</td>
                <td><input type="text" name="discount_rate"  value="<?php echo $drate; ?>">%</td>
            </tr>
        </table>
            <script>
                function del_rule(attr){
                    console.log(attr);
                    var con = confirm("Are you sure, you want to delete?");
                    if(con){
                        window.location.replace("<?php echo admin_url('admin.php?page=wc-settings&tab=discount') ; ?>&del_cnt="+attr);
                    }
                }
            </script>
        <?php

}

add_action( 'woocommerce_update_options_discount', 'update_settings' );
 
function update_settings(){
    if($_POST['cart_discount'] && $_POST['discount_rate']){
        $discount_setting = array_values(get_option('discount_settings'));
       
        $discount_setting[] = array(
            'custom_cart_discount'=>$_POST['cart_discount'],
            'custom_discount_rate'=>$_POST['discount_rate']
        );
        update_option('discount_settings',$discount_setting);
        
        if($_GET['cnt']){
            unset($discount_setting[($_GET['cnt']-1)]);
            ?>
                <script>
                    jQuery(function(){
                        window.location.replace("<?php echo admin_url('admin.php?page=wc-settings&tab=discount'); ?>");
                    });
                </script>
         <?php }
    }
}

