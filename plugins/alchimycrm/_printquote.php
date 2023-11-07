<?php
global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;
require('../../../wp-load.php');
$post = $_GET['p'];
$postInfos = get_post($post);
$acfs = get_fields($post->ID);
$postInfos->acfs = $acfs;
// var_dump($postInfos);
// die();
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
    // $patterns[0] = '/<li>(?!<span>)/';
    // $patterns[1] = '/(?!<\/span>)<\/li>/';
    // $val = preg_replace($patterns, array('<li><span>', '</span></li>'), $val);
    return $val;
}


$html2pdf = new Html2Pdf();
$html2pdf->addFont('aktivgrotesk-bold', '',  'aktivgrotesk-bold.php');
$html2pdf->addFont('aktivgrotesk-regular', '',  'aktivgrotesk-regular.php');
$html2pdf->addFont('aktivgrotesk-light', '',  'aktivgrotesk-light.php');
//$html2pdf->SetFontSpacing(3);
$html2pdf->setDefaultFont('aktivgrotesk-bold');
$content = '
<style type="text/css">
p.listitem {
    position: relative;
    margin-left: 5px;
    margin-bottom: 10px;
    margin-top: 0;
   
}
p.listitem span {
    position: absolute;
    left: -5px;
}
.light {
    font-family: aktivgrotesk-light;
}
.logo {
    width: 150px;
}
.devis {
    border-collapse: collapse;
    width: 100%;
}
.devis td, .devis th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
div.title {
    width: 80%;
    margin: auto;
    margin-top: 20%;
}
div.title h2 {
    font-family: aktivgrotesk-regular;
    font-size: 8pt;
    text-transform: uppercase;
    color: #C6C6C5;
    margin-bottom: 0;
    
}
div.title .client {
    font-size: 34pt;
    margin-top:20px;
    margin-bottom:0;
}
div.title .prestation {
    font-size: 34pt;
    margin-top:0;
}
#preamb {
    margin-left: 80px;
    width: 60%;
    font-family: aktivgrotesk-light;
}
#preamb h2 {
    font-size: 24pt;
}
#preamb h3 {
    font-size: 12pt;
    text-transform: uppercase;
}
div.entry ul li {
    font-family: aktivgrotesk-light;
}
ul li {
    font-size: 4pt;
}
ul li span {
    font-size: 8pt;
}
.infos {
    font-size: 7pt;
    font-family: aktivgrotesk-light;
}
.nums {
    font-size: 6pt;
    font-weight: bold;
}
.contenu h2 {
    font-size: 24pt;
}
.contenu h3 {
    font-size: 12pt;
    text-transform: uppercase;
}
#banque tr td {
    font-size: 8pt;
}
.entry p {
    margin:0;
}
</style>
<page>
<div>
    <img src="medias/logo.png" alt="" class="logo">
    <hr style="border-width:0.1pt; margin-top:10pt" />
    <table style="margin-top:10pt; font-size: 7pt; line-height:8.57pt; letter-spacing:0.1em">
            <tr>
                <td style="width:200px">
                <p class="light" style="">26 rue de la Bolle<br />88100 St-Dié-des-Vosges<br />+33 (0)6 13 97 61 70<br />hello@agence-alchimy.fr</p>            
                </td>
                <td style="width:370px">
                <p>www.agence-alchimy.fr<br /><br />Votre interlocuteur à l’agence<br /><span class="light">Gianina Plesca</span></p>
                </td>
                <td>
                <p style="text-align:right"><br />SARL au capital de 10.000€<br /><span class="light">848 912 143 RCS ÉPINAL</span><br /><span class="light">Siren : 848 912 143 00023  I   APE : 7021Z</span></p>
                </td>
            </tr>
    
        </table>
</div>
<div class="title">
    <h2>Offre de prestation</h2>
    <p class="client">'.$postInfos->acfs['client'][0]->post_title.'</p>
    <p class="prestation light">'.$postInfos->post_title.'</p>
</div>


<div style="width:100%; height:300px; background-color:black; margin:auto; position:absolute; bottom:0">
    <div style="width:50%; color: white; position: absolute; padding:20pt; top:0;">
        <h3 style="color: #C6C6C5; text-transform: uppercase; font-size: 6pt; margin:0; margin-bottom: 30px">À l\'attention de</h3> 
        <p style="font-size: 13pt; margin:0; margin-bottom:5pt">'.$postInfos->acfs['client'][0]->post_title.'</p>
        <p class="light" style="margin:0; margin-bottom:5pt">'.get_field('adresse', $postInfos->acfs['client'][0]->ID).'</p>
        <p class="light" style="margin:0">'.get_field('code_postal', $postInfos->acfs['client'][0]->ID).' '.get_field('ville', $postInfos->acfs['client'][0]->ID).'</p>
    </div>
    <div style="width:50%; color: white; position: absolute; padding:20pt; top:0; right:0; text-align:right">
        <p style="margin:0; margin-bottom:5pt">Devis // '.$postInfos->acfs['reference'].'</p>
        <p style="margin:0; margin-bottom:5pt">Date // '.$postInfos->acfs['Date'].'</p>
        <p style="margin-top:32pt">Validité de l\'offre : 30j.</p>

    </div>
    <div style="height:0.1px; background-color:white; position:absolute; top:70%; width:700px; left:20pt"></div>
    
    <p style="position:absolute; top:80%; padding-left: 20pt; padding-right: 20pt; color: white; font-size: 6pt" class="light">L’ensemble des informations remises à travers ce document demeurent la propriété exclusive de l’agence Alchimy. Vous n’êtes autorisés en aucun cas à diffuser ou divulguer à un tiers les éléments communiqués sans autorisation écrite de notre agence.</p>
</div>    
</page><page ><div id="preamb">'.addSpanToList(get_field('introduction', $postInfos->ID)).'</div></page><page backtop="20mm" backbottom="20mm" backleft="20mm" backright="20mm">
<page_header>
        <table style="width: 100%; border: 0;">
            <tr>
                <td style="text-align: left;    width: 23%"><img src="medias/logo.png" alt="" width="70"></td>
                <td style="text-align: left;    width: 44%" class="infos">Devis // <strong>'.$postInfos->acfs['reference'].'</strong><br />Date // <strong>'.$postInfos->acfs['Date'].'</strong></td>
                <td style="text-align: right;    width: 33%" class="nums">page [[page_cu]]/[[page_nb]]</td>
                
            </tr>
        </table>
        <hr style="border-width:0.1pt; margin-top:10pt" />
    </page_header>
    <page_footer>
        <p class="light" style="font-size: 6pt">L’ensemble des informations remises à travers ce document demeurent la propriété exclusive de l’agence Alchimy. Vous n’êtes autorisés en aucun cas à diffuser ou divulguer à un tiers les éléments communiqués sans autorisation écrite de notre agence.</p>
    </page_footer>
<div class="contenu">
';
$entries = get_field('entree', $postInfos->ID);
$i = 0;
foreach($entries as $entry){
    $margin = ($i>0) ? 'margin-top: 4pt' : '';
    $content .= '<div class="entry" style="margin-top: 10pt";>';
    $content .= '<h2 style="'.$margin.'">'.$entry['titre'].'</h2>';
    $i++;
    $prestations = $entry['prestations'];
    $npresta = count($prestations);
    $j = 0;
    foreach($prestations as $prestation){
        //$content .= print_r($prestation);
        $j++;
        $content .= '<table style="width: 100%; border: 0;"><tr><td style="width:60%"><h3>'.$prestation['prestation']->post_title.'</h3></td><td style="width:20%; text-align: right">';
        $n = 1;
        if($prestation['nombre'] > 1){
            $content .= '<h3>'.$prestation['nombre'].'x</h3>';
            $n = $prestation['nombre'];
        }
        if(is_numeric($prestation['tarif'])){
            $content .= '</td><td style="width: 20%; text-align: right"><h3>'.$n*$prestation['tarif'].' &euro; HT</h3></td></tr></table>';
        }
        else{
            $content .= '</td><td style="width: 20%; text-align: right"><h3>'.$prestation['tarif'].'</h3></td></tr></table>';
        }
        
        //$content .= '<div>'.addSpanToList($prestation['prestation']->post_content).'</div>';
        $content .= '<div>'.addSpanToList($prestation['description']).'</div>';
        if($j < $npresta){
            $content .= '<hr style="border-width:0.1pt; margin-top:20pt; margin-bottom: 20pt" />';
        }
        //$content .= '<h4>'.
    }
    $content .= '</div>';
}
//$content .= var_dump($entries);
//die();
$content .= '</div></page>';
$content .= '<page backtop="20mm" backbottom="10mm" backleft="10mm" backright="10mm">
<page_header>
        <table style="width: 100%; border: 0;">
            <tr>
                <td style="text-align: left;    width: 23%"><img src="medias/logo.png" alt="" width="70"></td>
                <td style="text-align: left;    width: 44%" class="infos">Devis // <strong>'.$postInfos->acfs['reference'].'</strong><br />Date // <strong>'.$postInfos->acfs['Date'].'</strong></td>
                <td style="text-align: right;    width: 33%" class="nums">page [[page_cu]]/[[page_nb]]</td>
                
            </tr>
        </table>
        <hr style="border-width:0.1pt; margin-top:10pt" />
    </page_header>';
$content .= '<div style="width:100%; height:300px; position:absolute; bottom:0; "><p style="text-align: center; color:#C6C6C5; font-size: 6pt" class="light">LA SIGNATURE DE CE DEVIS VAUT POUR ACCEPTATION DES CONDITIONS GÉNÉRALES DE VENTES JOINTES.</p><div style=" background-color:#f7f7f7; margin:auto; padding: 15pt; margin-bottom: 25px;">
<table style="width: 100%"; border:0">
    <tr>
        <td style="width:60%">
            <p style="font-size: 8pt; margin-bottom: 0;">DATE ET SIGNATURE :</p>
            <p style="font-size: 8pt; margin-top: 3pt;" class="light">Accompagné de la mention<br />"lu et approuvé"</p>
            <p style="font-size: 7pt; margin-top: 200px;"><strong>Conditions de réglement</strong> <span class="light">: 30 jours</span><br /><strong>Mode de réglement</strong> <span class="light">: Chèque ou Virement bancaire</span></p>
        </td>
        <td style="width:20%; vertical-align:middle;">
            <p style="margin:0; margin-bottom: 5pt;" class="light">Total HT :</p>
            <p style="margin:0; margin-bottom: 5pt;" class="light">TVA (20%) :</p>
            ';
if($postInfos->acfs['total_remise'] > 0){
    $content .= '<p style="margin:0; margin-bottom: 5pt;" class="">Total remise :</p>';
}
$content .= '
            <p style="margin:0; margin-bottom: 5pt;" class="">Total TTC :</p>
            <br />
            <p style="margin:0; margin-bottom: 5pt;" class="">Acompte (50%) :</p>
        </td>
        <td style="width: 15%; vertical-align:middle; text-align: right;">
            <p style="margin:0; margin-bottom: 5pt;" class="light">'.$postInfos->acfs['total_ht'].' &euro;</p>
            <p style="margin:0; margin-bottom: 5pt;" class="light">'.$postInfos->acfs['total_ht']*0.2.' &euro;</p>';
            if($postInfos->acfs['total_remise'] > 0){
                $content .= '<p style="margin:0; margin-bottom: 5pt;" class="">'.sprintf("%0.2f", $postInfos->acfs['total_remise']).' &euro;</p>';
            }
            $content .= '
            <p style="margin:0; margin-bottom: 5pt;" class="light">'.sprintf("%0.2f", $postInfos->acfs['total_ttc']).' &euro;</p>
            <br />
            <p style="margin:0; margin-bottom: 5pt;" class="light">'.sprintf("%0.2f", $postInfos->acfs['total_ttc']/2).' &euro;</p>
            
        </td>
    </tr>
</table>
</div>
<table style="width: 100%"; border:0; " id="banque">
    <tr>
        <td style="width: 33%; font-size: 9pt; color: #878786" rowspan="2">CIC - SAINT-DIÉ-DES-VOSGES</td>
        <td style="width: 7%" class="light">Banque</td>
        <td style="width: 7%" class="light">Guichet</td>
        <td style="width: 10%" class="light">Compte</td>
        <td style="width: 5%" class="light">Clé</td>
        <td style="border-left: 1px solid black; padding-left: 15px;" rowspan="2" ><p style="margin:0; margin-bottom:5px;"><span class="light">IBAN: </span>FR76 3008 7336 5800 0208 6730 305</p><p style="margin:0;"><span class="light">BIC: </span>CMCIFRPP</p></td>
    </tr>
    <tr>
        <td>30087</td>
        <td>33658</td>
        <td>3300020867303</td>
        <td>05</td>
        
    </tr>
</table>
</div>';
$content .= '</page>';
//echo $content;
$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->writeHTML($content);
$html2pdf->output();

?>