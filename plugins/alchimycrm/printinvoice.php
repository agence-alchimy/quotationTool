<?php
global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;
require('../../../wp-load.php');
$post = $_GET['p'];
$postInfos = get_post($post);
$acfs = get_fields($post->ID);
$postInfos->acfs = $acfs;
define('TVA', 0.2);
require __DIR__.'/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

$date = explode('/', $postInfos->acfs['Date']);
$m = $date[1]-1;
$mois = array('Jan.', 'Fév.', 'Mar.', 'Avr.', 'Mai.', 'Juin', 'Jui.', 'Aou.', 'Sep.', 'Oct.', 'Nov.', 'Déc.');
$postInfos->acfs['Date'] = $date[0] . ' ' . $mois[$m] . ' ' . $date[2];
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

$content = '';
$content .= get_pdf_template('styles');
$content .= get_pdf_template('entete_facture', array(
    'client'=>$postInfos->acfs['client'][0]->post_title, 
    'titre'=>$postInfos->post_title,
    'soustitre'=>$postInfos->acfs['complement_titre'],
    'adresse'=>get_field('adresse', $postInfos->acfs['client'][0]->ID),
    'cp'=>get_field('code_postal', $postInfos->acfs['client'][0]->ID),
    'ville'=>get_field('ville', $postInfos->acfs['client'][0]->ID),
    'reference'=>$postInfos->acfs['reference'],
    'date'=>$postInfos->acfs['Date'],
    'prescripteur'=>$postInfos->acfs['prescripteur'],
    'reference'=>$postInfos->acfs['reference'],
    'date'=>$postInfos->acfs['Date'],
    'total_remise'=>$postInfos->acfs['total_remise'],
    'total_ht'=>$postInfos->acfs['total_ht'],
    'total_ttc'=>$postInfos->acfs['total_ttc'],
    'acompte'=>$postInfos->acfs['acompte'],
    'total_a_regler'=>$postInfos->acfs['total_a_regler']

));
$content .= get_pdf_template('entrees_facture', array(
    'reference'=>$postInfos->acfs['reference'],
    'date'=>$postInfos->acfs['Date'],
    'services'=>$postInfos->acfs['services']
));
$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->writeHTML($content);
$html2pdf->output($postInfos->acfs['reference'].'.pdf');