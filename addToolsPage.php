<?php

/*
 * Add new payment report option to tools
 */

function add_tools_option(){
     // Add a new submenu under Tools:
    add_management_page('Payment Report', 'Payment Report', 'manage_options', 'payreport', 'pay_report_calback');
}
        
add_action('admin_init','add_tools_option');

function pay_report_calback(){
        
    $search_criteria = array(
        'status' => 'active'
    );
    $sdate = '';
    $edate = '';
    
    if(isset($_POST['start_date'])){
        $sdate = $_POST['start_date'] ;
        $search_criteria['start_date'] = wsi_get_gmt_date($sdate.' 00:00:00');
    }

    if(isset($_POST['end_date'])){
        $edate = $_POST['end_date'];
        $search_criteria['end_date'] = wsi_get_gmt_date($edate.' 23:59:59');
    }
    
    ?>
    <h1>Payment Report</h1>
    <form method="post" style="margin: 5%">
    <table>
        <tr>
            <td>
                <input type="text" placeholder="start date (YYYY-mm-dd)" name="start_date" value="<?php echo $sdate;?>">
            </td>
            <td>
                <input type="text" placeholder="End date (YYYY-mm-dd)" name="end_date" value="<?php echo $edate;?>">
            </td>
            <td>
                <input type="submit" value="search" name="search">
            </td>
        </tr>
    </table>
    </form>
    <table border="solid 1px black" style="width: 75%; margin: 5%">
        <tr style="background: silver;color: black">
            <td>Student#</td>
            <td>Student Name</td>
            <td>Amount</td>
            <td>Transaction ID</td>
            <td>Campus</td>
            <td>Date</td>
        </tr>
        <?php
        
            global $wpdb;

            date_default_timezone_set('America/New_York'); 

            $lead_table_name = $wpdb->prefix . "rg_lead";
            $lead_detail_table_name = $wpdb->prefix . "rg_lead_detail";
            $form_id = 19;

            // fetch all the entries for current day from 12:00 am to 11:59pm
            $entries = GFFormsModel::search_leads($form_id,$search_criteria); 
            
            if(!empty($entries)){
            foreach ($entries as $en){ ?>
        <tr>
            <td><?php echo $en[27]; ?></td>
            <td><?php echo $en[14]; ?></td>
            <td><?php echo $en['payment_amount']; ?></td>
            <td><?php echo $en['transaction_id']; ?></td>
            <td><?php $campus = explode("|", $en[17]); echo $campus[0]; ?></td>
            <td><?php echo wsi_format_date($en['date_created'],TRUE,'Y-m-d h:i A');  ?></td>
        </tr>
           <?php }
            }else{
                echo '<tr><td colspan="6" align="center">Sorry No matching entry found!!</td></tr>';
            }
           ?>
    </table>
 <?php }
