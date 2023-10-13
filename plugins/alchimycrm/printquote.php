<?php
global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;
require('../../../wp-load.php');
$post = $_GET['p'];
$postInfos = get_post($post);
$acfs = get_fields($post);
$postInfos->acfs = $acfs;
define('TVA', 0.2);
require __DIR__.'/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

function addSpanToList($val){
    $patterns = array();
    $patterns[0] = '/<ul>/';
    $patterns[1] = '/<\/ul>/';
    $patterns[2] = '/<li>/';
    $patterns[3] = '/<\/li>/';
    $patterns[4] = '/<!-- .* -->/';
    $val = preg_replace($patterns, array('', '', '<p class="listitem"><span>&bull;</span>&nbsp;', '</p>', ''), $val);
    
    return $val;
}
function get_pdf_template($templateName, $args = array())
{
    extract($args);    
    ob_start();
    include_once plugin_dir_path(__FILE__ ) . 'templates/' . $templateName . ".php";
    return ob_get_clean();
}

$html2pdf = new Html2Pdf();
$html2pdf->addFont('aktivgrotesk-bold', '',  '/fonts/aktivgrotesk-bold.php');
$html2pdf->addFont('aktivgrotesk-regular', '',  '/fonts/aktivgrotesk-regular.php');
$html2pdf->addFont('aktivgrotesk-light', '',  '/fonts/aktivgrotesk-light.php');
//$html2pdf->SetFontSpacing(3);
$html2pdf->setDefaultFont('aktivgrotesk-bold');

$date = explode('/', $postInfos->acfs['Date']);
$m = $date[1]-1;
$mois = array('Jan.', 'Fév.', 'Mar.', 'Avr.', 'Mai.', 'Juin', 'Jui.', 'Aou.', 'Sep.', 'Oct.', 'Nov.', 'Déc.');
$postInfos->acfs['Date'] = $date[0] . ' ' . $mois[$m] . ' ' . $date[2];

$content = '';
$content .= get_pdf_template('styles');
$content .= get_pdf_template('entete', array(
    'client'=>$postInfos->acfs['client'][0]->post_title, 
    'titre'=>$postInfos->post_title,
    'adresse'=>get_field('adresse', $postInfos->acfs['client'][0]->ID),
    'cp'=>get_field('code_postal', $postInfos->acfs['client'][0]->ID),
    'ville'=>get_field('ville', $postInfos->acfs['client'][0]->ID),
    'reference'=>$postInfos->acfs['reference'],
    'date'=>$postInfos->acfs['Date'],
    'prescripteur'=>$postInfos->acfs['prescripteur']

));

$introduction_field = get_field('introduction', $postInfos->ID);
if(!empty($introduction_field) && strlen(trim($introduction_field)) > 0){

    $content .= get_pdf_template('preambule', array(
        'intro'=>addSpanToList(get_field('introduction', $postInfos->ID)),
        'date'=>$postInfos->acfs['Date'],
        'reference'=>$postInfos->acfs['reference'],
    ));
}

if(!empty(get_field('entree', $postInfos->ID))){
    $content .= get_pdf_template('entrees', array(
        'reference'=>$postInfos->acfs['reference'],
        'date'=>$postInfos->acfs['Date'],
        'entrees'=>get_field('entree', $postInfos->ID)
    ));
}

if(!empty($postInfos->acfs['prestations'])){
    $content .= get_pdf_template('options', array(
        'reference'=>$postInfos->acfs['reference'],
        'date'=>$postInfos->acfs['Date'],
        'entrees'=> $postInfos->acfs['prestations']
    ));
}
if(!empty($postInfos->acfs['total_ht'])){
    $content .= get_pdf_template('total', array(
        'reference'=>$postInfos->acfs['reference'],
        'date'=>$postInfos->acfs['Date'],
        'total_remise'=>$postInfos->acfs['total_remise'],
        'total_ht'=>$postInfos->acfs['total_ht'],
        'total_ttc'=>$postInfos->acfs['total_ttc']
    ));
}
$content .= get_pdf_template('cgv');
// echo $content;
// die();
$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->writeHTML($content);
$html2pdf->output($postInfos->acfs['reference'].'.pdf');