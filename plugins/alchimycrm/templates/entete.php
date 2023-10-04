<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm" id="acompte">
<div>
    <img src="medias/logo.png" alt="" class="logo">
    <hr style="border:0; border-bottom:0.5pt; border-color: #1c1d20; margin-top:10pt; margin-bottom: 0;" />
    <table style="margin-top:5mm; font-size: 7pt; line-height:11pt; width:100%;">
            <tr>
                <td style="width:25%">
                <p class="light" style="margin-top:0;">26 rue de la Bolle<br />88100 St-Dié-des-Vosges<br />+33 (0)6 13 97 61 70<br />hello@agence-alchimy.fr</p>            
                </td>
                <td style="width:35%">
                <p style="margin-top:0;">www.agence-alchimy.fr<br /><br />Votre interlocuteur à l’agence<br /><span class="light"><?php echo $prescripteur[0]['display_name']; ?></span></p>
                </td>
                <td style="width:40%">
                <p style="text-align:right; margin-top:0"><br />SARL au capital de 10.000€<br /><span class="light">848 912 143 RCS ÉPINAL</span><br /><span class="light">Siren : 848 912 143 00023  I   APE : 7021Z</span></p>
                </td>
            </tr>
    
        </table>
</div>
<div class="title" style="margin-top: 55mm;">
    <h2>Offre de prestations</h2>
    <p class="client"><?php echo $client; ?></p>
    <p class="prestation light"><?php echo $titre; ?></p>
</div>


<div style="width:100%; height:68.5mm; background-color:black; margin:auto; position:absolute; bottom:0">
    <div style="width:50%; color: white; position: absolute; padding:20pt; top:0;">
        <h3 style="color: #C6C6C5; text-transform: uppercase; font-size: 6pt; margin:0; margin-bottom: 30px">À l'attention de</h3> 
        <p style="font-size: 13pt; margin:0; margin-bottom:5pt"><?php echo $client; ?></p>
        <p class="light" style="margin:0; margin-bottom:5pt"><?php echo $adresse; ?></p>
        <p class="light" style="margin:0"><?php echo $cp; ?> <?php echo $ville; ?></p>
    </div>
    <div style="width:50%; color: white; position: absolute; padding:20pt; top:0; right:0; text-align:right">
        <p style="margin:0; margin-bottom:5pt">Devis // <?php echo $reference; ?></p>
        <p style="margin:0; margin-bottom:5pt">Date // <?php echo $date; 
        //echo $date; ?></p>
        <p style="margin-top:32pt">Validité de l'offre : 30j.</p>

    </div>
    <div style="height:0.5pt; background-color:#c6c6c5; position:absolute; top:68%; width:625px; left:20pt"></div>
    
    <p style="position:absolute; top:80%; padding-left: 20pt; padding-right: 20pt; color: white; font-size: 6pt" class="light">L’ensemble des informations remises à travers ce document demeurent la propriété exclusive de l’agence Alchimy. Vous n’êtes autorisés en aucun cas à diffuser ou divulguer à un tiers les éléments communiqués sans autorisation écrite de notre agence.</p>
</div>    
</page>