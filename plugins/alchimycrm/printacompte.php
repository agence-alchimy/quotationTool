<?php

global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;
require('../../../wp-load.php');
$post = $_GET['p'];
$postInfos = get_post($post);
$acfs = get_fields($postInfos->ID);
$postInfos->acfs = $acfs;
define('TVA', 0.2);
require __DIR__.'/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

function get_pdf_template($templateName, $args = array())
{
    extract($args);    
    ob_start();
    include_once plugin_dir_path(__FILE__ ) . 'templates/' . $templateName . ".php";
    return ob_get_clean();
}

$html2pdf = new Html2Pdf();
$html2pdf->addFont('aktivgrotesk-bold', '',  'aktivgrotesk-bold.php');
$html2pdf->addFont('aktivgrotesk-regular', '',  'aktivgrotesk-regular.php');
$html2pdf->addFont('aktivgrotesk-light', '',  'aktivgrotesk-light.php');
//$html2pdf->SetFontSpacing(3);
$html2pdf->setDefaultFont('aktivgrotesk-bold');

$date = explode('/', $postInfos->acfs['Date']);
$m = $date[1]-1;
$mois = array('Jan.', 'Fév.', 'Mar.', 'Avr.', 'Mai.', 'Juin', 'Jui.', 'Aou.', 'Sep.', 'Oct.', 'Nov.', 'Déc.');
$postInfos->acfs['Date'] = $date[0] . ' ' . $mois[$m] . ' ' . $date[2];
//echo $postInfos->acfs['reference'];

$quote = get_posts(array(
    'numberposts'   => -1,
    'post_type'     => 'devis',
    'id'      => $postInfos->acfs['ref_devis']
));

if(!isset($quote) || count($quote) <= 0 || !key_exists(0, $quote)){
    return wp_send_json_error('Devis non trouvé; vérifiez la réfèrence.', 404);
}

$quote[0]->fields = get_fields($quote[0]->ID);

$content = '';
$content .= get_pdf_template('styles');
$content .= get_pdf_template('entete_acompte', array(
    'client'=>$postInfos->acfs['client'][0]->post_title, 
    'titre'=>$postInfos->post_title,
    'adresse'=>get_field('adresse', $postInfos->acfs['client'][0]->ID),
    'cp'=>get_field('code_postal', $postInfos->acfs['client'][0]->ID),
    'ville'=>get_field('ville', $postInfos->acfs['client'][0]->ID),
    'reference'=>$postInfos->acfs['reference'],
    'date'=>$postInfos->acfs['Date'],
    'prescripteur'=>$postInfos->acfs['prescripteur'],
    'total_ht'=>$postInfos->acfs['total_ht'],
    'total_ttc'=>$postInfos->acfs['total_ttc'],
    'pourcentage'=>$postInfos->acfs['pourcentage'],
    'ref_devis'=>$quote[0]->fields['reference'],
    'date_devis'=>$quote[0]->fields['Date']
));

$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->writeHTML($content);
$html2pdf->output($postInfos->acfs['reference'].'.pdf');