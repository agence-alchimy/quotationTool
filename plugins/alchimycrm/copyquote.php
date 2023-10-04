<?php
global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header, $post;
require('../../../wp-load.php');

$invoiceID = $_GET['p'];
$quoteID = $_GET['quote'];

$quote = get_post($quoteID);
$quote->infos = get_fields($quoteID);
// var_dump(array(
//     'ID'            => $invoiceID,
//     'post_title'    => $quote->post_title,   
//     'post_type'     => 'facture'
// ));
// die();

if(isset($invoiceID)){
    //die('ok');
    wp_insert_post(array(
        'ID'            => $invoiceID,
        'post_title'    => $quote->post_title,   
        'post_type'     => 'facture'
    ));
    //die($quote->post_title);
}
else {
    //$invoiceID = wp_update_post(array(
$invoiceID = wp_insert_post(array(
    'post_title'    => $quote->post_title, 
    'post_type'     => 'facture'  
));
    
}
update_field('client', $quote->infos['client'], $invoiceID);
update_field('prescripteur', $quote->infos['prescripteur'], $invoiceID);

$entries = get_field('entree', $quoteID);
$prestas = array();
foreach($entries as $entry){

    foreach($entry['prestations'] as $prestation){
        //var_dump($prestation);
        $presta = array(
            'titre' => $prestation['titre'],
            'tarif' => $prestation['tarif'],
            'nombre' => $prestation['nombre'],           
        );
        //echo $prestation['prestation']->post_title;
        if( empty($prestation['nombre'])){
            $presta['nombre'] = '1';
        }
        //var_dump($presta);
        add_row('services', $presta, $invoiceID);
    }
    //die();
    // foreach($entry['prestations'] as $k=>$prestation){
    //     $presta = array(
    //         'titre_fac' => $prestation['titre'],
    //         'tarif' => $prestation['tarif'],
    //         'nombre' => $prestation['nombre']
    //     );
    //     if( empty($prestation['nombre'])){
    //         $presta['nombre'] = '1';
    //     }
    //     update_row('prestations', $k, $presta);
    // }
}
//die();
// $options = get_field('prestations', $quoteID);
// $options_fac = array();
// foreach($options as $option){
//     $presta = array(
//         'titre' => $option['titre']->post_title,
//         'tarif' => $option['tarif'],
//         'nombre' => '1'
//     );
//     add_row('prestations', $presta, $invoiceID);
// }
update_field('remise', $quote->infos['remise'], $invoiceID);
update_field('pourcentmontant', $quote->infos['pourcentmontant'], $invoiceID);
update_field('total_remise', $quote->infos['total_remise'], $invoiceID);
update_field('total_ht', $quote->infos['total_ht'], $invoiceID);
update_field('total_ttc', $quote->infos['total_ttc'], $invoiceID);

//die();
header('Location:'.get_admin_url().'post.php?post='.$invoiceID.'&action=edit');
