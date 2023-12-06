<?php
/**
 * Plugin Name: Alchimy CRM
 * Description: Customers, quotes and invoices management
 * Version: 1.0
 * Author: Mathias Clavelin
 * Text Domain: acrm
 */

add_action( 'admin_enqueue_scripts', 'acrm_enqueue' );
function acrm_enqueue( $hook ) {
	
	wp_enqueue_script(
		'app',
		plugins_url( '/admin/app.js', __FILE__ ),
		array( 'jquery' ),
		filemtime(__DIR__ . '/admin/app.js'),
		true
	);

    wp_enqueue_style(
		'admin',
		plugins_url( '/css/admin.css', __FILE__ ),
		array(),
		filemtime(__DIR__ . '/css/admin.css')
	);


}
// add_action('acfe/fields/button/name=copier', 'my_acf_button_ajax', 10, 2);
// function my_acf_button_ajax($field, $post_id){

//     // retrieve field input value 'my_field'
//     $my_field = get_field('prestation');
    
//     // send json success message
//     wp_send_json_success("Success! My Field value is: {$my_field}");
//     //wp_send_json_success("Success!");
    
// }
function add_ajax()
{
   wp_localize_script(
    'function',
    'ajax_script',
    array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
//add_action('wp_ajax_nopriv_retrieveDesc', 'sendDesc');
add_action('wp_ajax_retrieveDesc', 'sendDesc');
function sendDesc(){
    $post_title = sanitize_title($_POST['post']);

    // $args = array("post_type" => "service", "post_name" => $post_title);
    // var_dump($args);
    // $posts = get_posts($args);
    // foreach($posts as $k=>$post){
    //     $posts[$k]->tarif = get_field('tarif_ht', $post->ID);
    // }
    // var_dump($posts);
    //wp_send_json_success($posts[0]);
    
    global $wpdb;
    $reqp = "SELECT ID, post_content, post_title FROM {$wpdb->posts} WHERE post_name = '{$post_title}' AND post_type='service'";
    $req = $wpdb->get_results($reqp);
    $req[0]->tarif = get_field('tarif_ht', $req[0]->ID);
   // var_dump($req);
    
    wp_send_json_success($req);
}
//$dirName = get_stylesheet_directory();  // use this to get child theme dir
// require_once ($dirName."/ajax.php");  

// add_action("wp_ajax_nopriv_function1", "function1"); // function in ajax.php

add_action('template_redirect', 'add_ajax');  
/*
require __DIR__.'/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf();
$html2pdf->addFont('aktivgrotesk-bold', '',  'aktivgrotesk-bold.php');
$html2pdf->addFont('aktivgrotesk-regular', '',  'aktivgrotesk-regular.php');
$html2pdf->addFont('aktivgrotesk-light', '',  'aktivgrotesk-light.php');
$html2pdf->setDefaultFont('aktivgrotesk-bold');
$html2pdf->writeHTML('<h1>Hélèna pô...</h1><p style="font-family:aktivgrotesk-light;">This is my first test</p>');
$html2pdf->output();
*/
// Adds a meta box for quote's functionnalities
function acrm_add_custom_box() {
	$screens = [ 'devis', 'facture', 'acompte'];
	foreach ( $screens as $screen ) {
        if($screen == 'devis'){
            add_meta_box(
                'quotes_box_id',                 // Unique ID
                'Devis',      // Box title
                'acrm_custom_box_devis_html',  // Content callback, must be of type callable
                $screen,
                'side'                            
            );
        }
        if($screen == 'facture'){
            add_meta_box(
                'quotes_box_id',                 // Unique ID
                'Facture',      // Box title
                'acrm_custom_box_facture_html',  // Content callback, must be of type callable
                $screen,
                'side'                            
            );
        }
        if($screen == 'acompte'){
            add_meta_box(
                'quotes_box_id',                 // Unique ID
                'Acompte',      // Box title
                'acrm_custom_box_acompte_html',  // Content callback, must be of type callable
                $screen,
                'side'                            
            );
        }
	}
}

add_action( 'add_meta_boxes', 'acrm_add_custom_box' );
// Adds print button
function acrm_custom_box_devis_html( $post ) {
	?>
	<div id="major-quote-actions">
        <div id="print-action">
            <br>
            <a href="<?php echo plugin_dir_url(__FILE__); ?>printquote.php?p=<?php echo $post->ID; ?>" target="_blank" class="button button-primary button-large">Imprimer</a>
        </div>
        <div>            
            <p>Devis envoyé ?</p>
            <input type="checkbox" name="quotesent" id="" <?php echo (get_post_meta( $post->ID, 'quotesent', true ) == '1') ? 'checked' : ''; ?>>
        </div>
        <div>            
            <p>Devis accepté ?</p>
            <input type="checkbox" name="quoteaccepted" id="" <?php echo (get_post_meta( $post->ID, 'quoteaccepted', true ) == '1') && get_post_meta( $post->ID, 'quoterefused', true ) == 0 ? 'checked' : ''; ?>>
        </div>
        <div class="quoterefused">            
            <p>Devis refusé</p>
            <input type="checkbox" name="quoterefused" id="" <?php echo (get_post_meta( $post->ID, 'quoterefused', true ) == '1') ? 'checked' : ''; ?>>
        </div>
    </div>
	<?php
}

// THIS IS DEACTIVATED BECAUSE ALL POPULATION SEEMS TO BE MADE IN JS
// TODO: Find a way to do neatly and not split code between backend and front-end.
//
// add_action( 'quick_edit_custom_box', 'add_custom_edit_box', 10, 3 );
//
function add_custom_edit_box( $column_name, $post_type, $taxonomy ) {
    global $post;

    switch ( $post_type ) {
        case 'post':
        case 'devis':

        if( $column_name === 'quotesent' ): 
        ?>

            <fieldset class="inline-edit-col-left" id="#edit-quotesent">
                    <label>
                    <?php var_dump($post) ?>
                        <span class="title"> Devis envoyé </span>
                            <input type="checkbox" name="quotesent" <?php echo (get_post_meta( $post->ID, 'quotesent', true ) == '1') ? 'checked' : ''; ?>>
                        </span>
                    </label>
            </fieldset>
            <?php
        endif;
        if( $column_name === 'quoteaccepted' ): 
            ?>
                <fieldset class="inline-edit-col-left" id="#edit-quoteaccepted">
                        <label>
                            <span class="title"> Devis accepté </span>
                                <input type="checkbox" name="quoteaccepted" <?php echo (get_post_meta( $post->ID, 'quoteaccepted', true ) == '1') ? 'checked' : ''; ?>>
                            </span>
                        </label>
                </fieldset>
                <?php
            endif;
        if( $column_name === 'quoterefused' ):
        ?>
            <fieldset class="inline-edit-col-left" id="#edit-quoterefused">
                    <label>
                        <?php echo get_post_meta( $post->ID, 'quoterefused', true ) ?>
                        <span class="title"> Devis refusé </span>
                            <input type="checkbox" name="quoterefused" <?php echo (get_post_meta( $post->ID, 'quoterefused') == 1) ? 'checked' : ''; ?>>
                        </span>
                    </label>
            </fieldset>
            <?php
        endif;
            break;
        
        default:
            break;
    }
}

function acrm_custom_box_facture_html( $post ) {

    $quotes = get_posts(array('post_type'=>'devis'));
    foreach($quotes as $k=>$quote){
        $num = get_field('reference', $quote->ID);
        $quotes[$k]->reference = $num;
    }
	?>
   
    <p>Sélectionner le devis à copier</p>
    <input type="hidden" value="?php echo $post->ID; ?>">
    <div>
        <select name="" id="selectquote">
            <option>---</option>
            <?php foreach($quotes as $quote): ?>
            <option value="<?php echo $quote->ID; ?>"><?php echo $quote->reference . ' ' . $quote->post_title; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <br>
	<div id="major-quote-actions">
        <div id="print-action">
            <a href="<?php echo plugin_dir_url(__FILE__); ?>copyquote.php?p=<?php echo $post->ID; ?>" target="_self" class="button button-primary button-large" id="copyquote" >Copier</a>
        
            <a href="<?php echo plugin_dir_url(__FILE__); ?>printinvoice.php?p=<?php echo $post->ID; ?>" target="_blank" class="button button-primary button-large">Imprimer</a>
        
        </div>
    </div>
    <div>            
        <p>Facture envoyée ?</p>
        <input type="checkbox" name="invoicesent" id="" <?php echo (get_post_meta( $post->ID, 'invoicesent', true ) == '1') ? 'checked' : ''; ?>>
    </div>
    <div>            
        <p>Facture payée ?</p>
        <input type="checkbox" name="invoicepaid" id="" <?php echo (get_post_meta( $post->ID, 'invoicepaid', true ) == '1') ? 'checked' : ''; ?>>
    </div>
	<?php
}
function acrm_custom_box_acompte_html( $post ) {

    // $quotes = get_posts(array('post_type'=>'devis'));
    // foreach($quotes as $k=>$quote){
    //     $num = get_field('reference', $quote->ID);
    //     $quotes[$k]->reference = $num;
    //     $quotes[$k]->client = get_field('client', $quote->ID);
    //     print_r($quotes[$k]->client);
    // }
    
	?>
   
    <!-- <p>Sélectionner le devis de référence</p>
    
    <div>
        <select name="" id="selectquote">
            <option>---</option>
            <?php foreach($quotes as $quote): ?>
            <option value="<?php echo $quote->ID; ?>"><?php echo $quote->client[0]->post_title . ' ' . $quote->reference . ' ' . $quote->post_title; ?></option>
            <?php endforeach; ?>
        </select>
    </div> -->
    
	<div id="major-quote-actions">
        <br>
        <div id="print-action">
        
            <a href="<?php echo plugin_dir_url(__FILE__); ?>printacompte.php?p=<?php echo $post->ID; ?>" target="_blank" class="button button-primary button-large">Imprimer</a>
        
        </div>
    </div> 
    <div>            
        <p>Facture d'acompte envoyée ?</p>
        <input type="checkbox" name="acomptesent" id="" <?php echo (get_post_meta( $post->ID, 'acomptesent', true ) == '1') ? 'checked' : ''; ?>>
    </div>
    <div>            
        <p>Facture d'acompte payée ?</p>
        <input type="checkbox" name="acomptepaid" id="" <?php echo (get_post_meta( $post->ID, 'acomptepaid', true ) == '1') ? 'checked' : ''; ?>>
    </div>
	<?php
}


CONST ACF_DEFAULT_DATE_FORMAT = 'Ymd';
// Automatically adds quote's number at it's creation
function acrm_set_quote_number( $post_id, $post, $update )  {
    
	if( ! $update ) {
        if($post->post_type == 'devis'){
            $args = array(
                'posts_per_page' => -1,
                'post_type' => 'devis',
                // 'date_query' => array(
                //     array(
                //         'year'  => get_the_date('Y', $post_id),
                //         'month' => get_the_date('m', $post_id)
                //     ),
                // ),
            );
            $nums = array();
            $num = 0;
            $quotes = get_posts( $args );
            if(count($quotes) > 0){
                foreach( $quotes as $quote ){
                    $nump = get_field('numero', $quote->ID );
                    $nums[] = $nump;
                }
                $num = max($nums)+1;
            }
            else{
                $num = 1;
            }
            //$nposts = count(get_posts( $args ));
            update_field('numero', $num, $post_id);
            $date = !is_null(get_field('date', $post_id)) ? DateTime::createFromFormat('d/m/Y', get_field('date', $post_id)) : new DateTime();
            $date = $date->format(ACF_DEFAULT_DATE_FORMAT);
            update_field('date', $date, $post_id);
            $ref = "D-".date('Y')."-".sprintf("%04d", $num);
            update_field('reference', $ref, $post_id);
        }
        else if($post->post_type == 'facture'){
            $args = array(
                'posts_per_page' => -1,
                'post_type' => 'facture',
                
            );
            $nums = array();
            $num = 0;
            $invoices = get_posts( $args );
            if(count($invoices) > 0){
                foreach( $invoices as $invoice ){
                    $nump = get_field('numero', $invoice->ID );
                    $nums[] = $nump;
                }
                $num = max($nums)+1;
            }
            else{
                $num = 1;
            }
            //$nposts = count(get_posts( $args ));
            update_field('numero', $num, $post_id);
            $date = !is_null(get_field('date', $post_id)) ? DateTime::createFromFormat('d/m/Y', get_field('date', $post_id)) : new DateTime();
            $date = $date->format(ACF_DEFAULT_DATE_FORMAT);
            update_field('date', $date, $post_id);
            $ref = "F-".date('Y')."-".sprintf("%04d", $num);
            update_field('reference', $ref, $post_id);
        }
        else if($post->post_type == 'acompte'){
            $args = array(
                'posts_per_page' => -1,
                'post_type' => 'acompte',
                
            );
            $nums = array();
            $num = 0;
            $invoices = get_posts( $args );
            if(count($invoices) > 0){
                foreach( $invoices as $invoice ){
                    $nump = get_field('numero', $invoice->ID );
                    $nums[] = $nump;
                }
                $num = max($nums)+1;
            }
            else{
                $num = 1;
            }
            //$nposts = count(get_posts( $args ));
            update_field('numero', $num, $post_id);
            $date = !is_null(get_field('date', $post_id)) ? DateTime::createFromFormat('d/m/Y', get_field('date', $post_id)) : new DateTime();
            $date = $date->format(ACF_DEFAULT_DATE_FORMAT);
            update_field('date', $date, $post_id);
            $ref = "FA-".date('Y')."-".sprintf("%04d", $num);
            update_field('reference', $ref, $post_id);
        }

	}
}
add_action( 'save_post', 'acrm_set_quote_number', 10, 3 );

// add_action( 'wp_insert_post_data', function ( $data, $postarr ) {
//     // do stuff
//     $patterns = array();
//     $patterns[0] = '/<li>(?!<span>)/';
//     $patterns[1] = '/(?!<\/span>)<\/li>/';
//     $fields = get_field($data['ID']);
//     var_dump($fields);
//     die();
//     if(isset($fields['introduction'])){
//         $introduction = preg_replace($patterns, array('<li><span>', '</span></li>'), $fields['introduction']);
//         update_field('introduction', $introduction, $data['ID']);
//     }
    
//     //return $data;
    
//  }, 10, 2 );
// Déclarer un bloc Gutenberg avec ACF
function alchimy_acf_block_types() {

    acf_register_block_type( array(
        'name'              => 'liste',
        'title'             => 'Liste',
        'description'       => "Présentation d'une extension WordPress",
        'render_template'   => 'blocks/liste.php',
        'category'          => 'text', 
        'icon'              => 'admin-plugins', 
        'keywords'          => array( 'plugin', 'extension', 'add-on' ),
        'enqueue_assets'    => function() {
        	wp_enqueue_style( 
                'capitaine-blocks', 
                plugin_dir_url(__FILE__) . '/css/blocks.css' 
            );
        }
    ) );
}

add_action( 'acf/init', 'alchimy_acf_block_types' );

/*
 * Add columns to quote post list
 */
function add_acf_devis_columns ( $columns ) {
    return array_merge ( $columns, array ( 
      'reference' => __ ( 'Référence' ),
      'client'   => __ ( 'Client' ),
      'Date_devis'    => __('Date'),
      'total_ht'    => __('Total HT'),
      'quotesent'   => __('Devis envoyé'),
      'quoteaccepted'   => __('Devis accepté'),
      'quoterefused'   => __('Devis refusé'),
    ) );
  }
add_filter ( 'manage_devis_posts_columns', 'add_acf_devis_columns' );
function add_acf_factures_columns ( $columns ) {
    return array_merge ( $columns, array ( 
      'reference' => __ ( 'Référence' ),
      'client'   => __ ( 'Client' ),
      'Date_facture'    => __('Date'),
      'total_ht'    => __('Total HT'),
      'invoicesent'   => __('Facture envoyée'),
      'invoicepaid'   => __('Facture payée'),
    ) );
  }
add_filter ( 'manage_facture_posts_columns', 'add_acf_factures_columns' );
function add_acf_acomptes_columns ( $columns ) {
    return array_merge ( $columns, array ( 
      'reference' => __ ( 'Référence' ),
      'client'   => __ ( 'Client' ),
      'Date_facture'    => __('Date'),
      'total_ht'    => __('Total HT'),
      'acomptesent'   => __('Facture envoyée'),
      'acomptepaid'   => __('Facture payée'),
      
    ) );
  }
add_filter ( 'manage_acompte_posts_columns', 'add_acf_acomptes_columns' );
function devis_custom_column ( $column, $post_id ) {
    switch ( $column ) {
        case 'reference':
            echo get_post_meta ( $post_id, 'reference', true );
            break;
        case 'client':
            $customer = get_post_meta ( $post_id, 'client', true );
            echo get_the_title($customer[0]);
            break;
        case 'Date_devis':
            $date = get_post_meta ( $post_id, 'Date', true );
            if(empty($date)){
                echo "Devis non daté";
            }
            else{
                echo substr($date, 6, 2).'/'.substr($date, 4, 2).'/'.substr($date, 0, 4);
            }           
            break;
        case 'quotesent':
            $quotesent = get_post_meta($post_id, 'quotesent', true);
            if($quotesent == '1') echo '<svg style="width:24px;height:24px" viewBox="0 0 24 24">
            <path fill="currentColor" d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
        </svg>';
            break;
        case 'quoteaccepted':
            $quoteaccepted= get_post_meta($post_id, 'quoteaccepted', true);
            if($quoteaccepted == '1') echo '<svg style="width:24px;height:24px" viewBox="0 0 24 24">
            <path fill="currentColor" d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
        </svg>';
            break;
        case 'quoterefused':
            $quoterefused= get_post_meta($post_id, 'quoterefused', true);
            if($quoterefused == '1') echo '<svg class="svg-icon" style="width:24px;height:24px;vertical-align: middle;fill: red;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M810.65984 170.65984q18.3296 0 30.49472 12.16512t12.16512 30.49472q0 18.00192-12.32896 30.33088l-268.67712 268.32896 268.67712 268.32896q12.32896 12.32896 12.32896 30.33088 0 18.3296-12.16512 30.49472t-30.49472 12.16512q-18.00192 0-30.33088-12.32896l-268.32896-268.67712-268.32896 268.67712q-12.32896 12.32896-30.33088 12.32896-18.3296 0-30.49472-12.16512t-12.16512-30.49472q0-18.00192 12.32896-30.33088l268.67712-268.32896-268.67712-268.32896q-12.32896-12.32896-12.32896-30.33088 0-18.3296 12.16512-30.49472t30.49472-12.16512q18.00192 0 30.33088 12.32896l268.32896 268.67712 268.32896-268.67712q12.32896-12.32896 30.33088-12.32896z"  /></svg>';
            break;
        case 'total_ht':
            echo get_field('total_ht', $post_id);
            break;
    }
  }
add_action ( 'manage_devis_posts_custom_column', 'devis_custom_column', 10, 2 );
function facture_custom_column ( $column, $post_id ) {
    switch ( $column ) {
        case 'reference':
            echo get_post_meta ( $post_id, 'reference', true );
            break;
        case 'client':
            $customer = get_post_meta ( $post_id, 'client', true );
            echo get_the_title($customer[0]);
            break;
        case 'Date_facture':
            $date = get_post_meta ( $post_id, 'Date', true );
            if(empty($date)){
                echo "Facture non datée";
            }
            else{
                echo substr($date, 6, 2).'/'.substr($date, 4, 2).'/'.substr($date, 0, 4);
            }           
            break;
        case 'invoicesent':
            $invoicesent = get_post_meta($post_id, 'invoicesent', true);
            if($invoicesent == '1') echo '<svg style="width:24px;height:24px" viewBox="0 0 24 24">
            <path fill="currentColor" d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
        </svg>';
            break;
        case 'invoicepaid':
            $invoicepaid= get_post_meta($post_id, 'invoicepaid', true);
            if($invoicepaid == '1') echo '<svg style="width:24px;height:24px" viewBox="0 0 24 24">
            <path fill="currentColor" d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
        </svg>';
            break;
        case 'total_ht':
            echo get_field('total_ht', $post_id);
            break;
    }
  }
add_action ( 'manage_facture_posts_custom_column', 'facture_custom_column', 10, 2 );
function acompte_custom_column ( $column, $post_id ) {
    switch ( $column ) {
        case 'reference':
            echo get_post_meta ( $post_id, 'reference', true );
            break;
        case 'client':
            $customer = get_post_meta ( $post_id, 'client', true );
            echo get_the_title($customer[0]);
            break;
        case 'Date_facture':
            $date = get_post_meta ( $post_id, 'Date', true );
            if(empty($date)){
                echo "Facture non datée";
            }
            else{
                echo substr($date, 6, 2).'/'.substr($date, 4, 2).'/'.substr($date, 0, 4);
            }           
            break;
        case 'acomptesent':
            $acomptesent = get_post_meta($post_id, 'acomptesent', true);
            if($acomptesent == '1') echo '<svg style="width:24px;height:24px" viewBox="0 0 24 24">
            <path fill="currentColor" d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
        </svg>';
            break;
        case 'acomptepaid':
            $acomptepaid= get_post_meta($post_id, 'acomptepaid', true);
            if($acomptepaid == '1') echo '<svg style="width:24px;height:24px" viewBox="0 0 24 24">
            <path fill="currentColor" d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
        </svg>';
            break;
        case 'total_ht':
            echo get_field('total_ht', $post_id);
            break;
    }
  }
add_action ( 'manage_acompte_posts_custom_column', 'acompte_custom_column', 10, 2 );
add_filter( 'manage_edit-devis_sortable_columns', 'acrm_devis_sortable_columns');
function acrm_devis_sortable_columns( $columns ) {
    $columns['reference'] = 'reference';
    $columns['client'] = 'client';
    $columns['Date_devis'] = 'Date_devis';
    $columns['total_ht'] = 'total_ht';
    $columns['quotesent'] = 'quotesent';
    $columns['quoteaccepted'] = 'quoteaccepted';
    $columns['quoterefused'] = 'quoterefused';
    return $columns;
}
add_action( 'pre_get_posts', 'acrm_posts_orderby' );
function acrm_posts_orderby( $query ) {
  if( ! is_admin() || ! $query->is_main_query() ) {
    return;
  }

  if ( 'reference' === $query->get( 'orderby') ) {
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', 'reference' );
    //$query->set( 'meta_type', 'numeric' );
  }
  if ( 'client' === $query->get( 'orderby') ) {
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', 'client' );
    //$query->set( 'meta_type', 'numeric' );
  }
  if ( 'Date_devis' === $query->get( 'orderby') ) {
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', 'Date' );
    $query->set( 'meta_type', 'date' );
  }
  if ( 'total_ht' === $query->get( 'orderby') ) {
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', 'total_ht' );
    $query->set( 'meta_type', 'numeric' );
  }
  if ( 'quotesent' === $query->get( 'orderby') ) {
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', 'quotesent' );
    $query->set( 'meta_type', 'numeric' );
  }
  if ( 'quoteaccepted' === $query->get( 'orderby') ) {
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', 'quoteaccepted' );
    $query->set( 'meta_type', 'numeric' );
  }
  if( 'quoterefused' === $query->get( 'orderby')){
    if($query->get('order') == "asc" ) {
        $query->set( 'meta_key', 'quoterefused' );
        $query->set( 'meta_value', '1' );
        $query->set( 'meta_compare', '==' );
      }
      else{
        $query->set( 'meta_key', 'quoterefused' );
        $query->set( 'meta_compare', 'NOT EXISTS' );
      }
  }
}


// Add meta "devis envoyé" to quote post type
add_action('wp_insert_post', 'acrm_add_custom_fields');
function acrm_add_custom_fields($post_id)
{
    if ( $_POST['post_type'] == 'devis' ) {
        add_post_meta($post_id, 'quotesent', '0', true);
        add_post_meta($post_id, 'quoteaccepted', '0', true);
        add_post_meta($post_id, 'quoterefused', '0', true);
    }
    if ( $_POST['post_type'] == 'facture' ) {
        add_post_meta($post_id, 'invoicesent', '0', true);
        add_post_meta($post_id, 'invoicepaid', '0', true);
    }
    if ( $_POST['post_type'] == 'acompte' ) {
        add_post_meta($post_id, 'acomptesent', '0', true);
        add_post_meta($post_id, 'acomptepaid', '0', true);
    }
    return true;
}

function save_acrm_meta( $post_id ) {

      
    if($_POST['post_type'] == 'devis'){

        if ( isset( $_REQUEST['quotesent'] ) ) {
            update_post_meta( $post_id, 'quotesent', '1' );
        }
        else {
            update_post_meta( $post_id, 'quotesent', '0' );
        }

        if ( isset( $_REQUEST['quoteaccepted'] ) && !isset( $_REQUEST['quoterefused'] ) ) {
            update_post_meta( $post_id, 'quoteaccepted', '1' );
        }
        else {
            update_post_meta( $post_id, 'quoteaccepted', '0' );
        }

         if ( isset( $_REQUEST['quoterefused'] ) ) {
            update_post_meta( $post_id, 'quoterefused', '1' );
        }
        else {
            update_post_meta( $post_id, 'quoterefused', '0' );
        }
    }

    if($_POST['post_type'] == 'facture'){
        if ( isset( $_REQUEST['invoicesent'] ) ) {
            update_post_meta( $post_id, 'invoicesent', '1' );
        }
        else {
            update_post_meta( $post_id, 'invoicesent', '0' );
        }
        if ( isset( $_REQUEST['invoicepaid'] ) ) {
            update_post_meta( $post_id, 'invoicepaid', '1' );
        }
        else {
            update_post_meta( $post_id, 'invoicepaid', '0' );
        }
    }
    if($_POST['post_type'] == 'acompte'){
        if ( isset( $_REQUEST['acomptesent'] ) ) {
            update_post_meta( $post_id, 'acomptesent', '1' );
        }
        else {
            update_post_meta( $post_id, 'acomptesent', '0' );
        }
        if ( isset( $_REQUEST['acomptepaid'] ) ) {
            update_post_meta( $post_id, 'acomptepaid', '1' );
        }
        else {
            update_post_meta( $post_id, 'acomptepaid', '0' );
        }
    }
    

}
add_action( 'save_post', 'save_acrm_meta' );
add_action( 'edit_post', 'save_acrm_meta' );
function my_acf_fields_post_object_result( $text, $post, $field, $post_id ) {
    $customer = get_field( 'client', $post->ID );

    if( $customer && count($customer) > 0) {
        $text .= ' ' . sprintf( '(%s)', $customer[0]->post_title );
    }
    return $text;
}
add_filter('acf/fields/post_object/result', 'my_acf_fields_post_object_result', 10, 4);

function update_service_slug_to_sanitized_title( $data, $postarr ) {

    if ( ! in_array( $data['post_status'], array( 'draft', 'pending', 'auto-draft' ) ) && $data['post_type'] == "service" ) {
        $data['post_name'] = sanitize_title( $data['post_title'] );
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'update_service_slug_to_sanitized_title', 99, 2 );