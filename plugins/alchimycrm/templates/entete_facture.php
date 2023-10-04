<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
    <page_footer>
        <p style="font-size: 6pt; text-align: center;">SARL au capital de 10.000€ <span class="light">I 848 912 143 RCS
                ÉPINAL I Siren : 848 912 143 00023 I APE : 7021Z</span></p>
    </page_footer>

    <div>
        <img src="medias/logo.png" alt="" class="logo">
        <hr style="border:0; border-bottom:0.5pt; border-color: #1c1d20; margin-top:10pt; margin-bottom: 0;" />
        <table style="margin-top:5mm; font-size: 7pt; line-height:11pt; width:100%;">
            <tr>
                <td style="width:25%">
                    <p class="light" style="margin-top:0;">26 rue de la Bolle<br />88100 St-Dié-des-Vosges<br />+33 (0)6
                        13 97 61 70<br />hello@agence-alchimy.fr</p>
                </td>
                <td style="width:35%">
                    <p style="margin-top:0;">www.agence-alchimy.fr<br /><br />Votre interlocuteur à l’agence<br /><span
                            class="light">
                            <?php echo $prescripteur[0]['display_name']; ?>
                        </span></p>
                </td>
                <td style="width:40%">
                    <p style="text-align:right; margin-top:0"><br />SARL au capital de 10.000€<br /><span
                            class="light">848 912 143 RCS ÉPINAL</span><br /><span class="light">Siren : 848 912 143
                            00023 I APE : 7021Z</span></p>
                </td>
            </tr>

        </table>
    </div>
    <div class="title">
        <p style="margin:0; font-size: 7pt; margin-bottom: 7pt;"><span class="light">Facture // </span>
            <?php echo $reference; ?>
        </p>
        <p style="margin:0; font-size: 7pt; margin-bottom: 30pt;"><span class="light">Date // </span>
            <?php echo $date; ?>
        </p>

        <p class="client">Facture</p>
        <p class="prestation light">
            <?php echo $titre; ?>
        </p>
        <p class="sous-titre" style="text-transform: uppercase; font-size: 9pt;">
            <?php echo (!empty($soustitre)) ? $soustitre : ''; ?>
        </p>

    </div>


    <div style="width:100%; height:300px; background-color:black; margin:auto; position:absolute; bottom:0">
        <table style="width: 100%; color:white" class="chiffresfac">
            <tr>
                <td style="width:50%">
                    <h3
                        style="color: #C6C6C5; text-transform: uppercase; font-size: 6pt; margin:0; margin-bottom: 30px">
                        À l'attention de</h3>
                    <p style="font-size: 13pt; margin:0; margin-bottom:5pt">
                        <?php echo $client; ?>
                    </p>
                    <p class="light" style="margin:0; margin-bottom:5pt">
                        <?php echo $adresse; ?>
                    </p>
                    <p class="light" style="margin:0">
                        <?php echo $cp; ?>
                        <?php echo $ville; ?>
                    </p>
                    <br>
                    <p style="font-size: 7pt">Conditions de règlement : A réception de facture<br>Mode de règlement :
                        Chèque ou Virement bancaire</p>
                </td>
                <td style="width:30%" class="chiffreslabels">
                    <p class="light">Total HT :</p>
                    <p class="light">TVA (20%) :</p>
                    <br>
                    <p class="light">Total TTC :</p>
                    <p class="light">Acompte versé :</p>
                    <br>
                    <p class="bold">Total à régler :</p>
                </td>
                <td style="width:20% " class="chiffres">
                    <p class="light">
                        <?php echo number_format($total_ht, 2, ',', '.'); ?> &euro;
                    </p>
                    <p class="light">
                        <?php echo number_format($total_ht * TVA, 2, ',', '.'); ?> &euro;
                    </p>
                    <br>
                    <p class="light">
                        <?php //echo sprintf("%0.2f", $total_ttc);
                        echo number_format($total_ttc, 2, ',', '.'); ?> &euro;
                    </p>
                    <p class="light">
                        <?php echo number_format($acompte, 2, ',', '.'); ?> &euro;
                    </p>
                    <br>
                    <p class="bold">
                        <?php echo number_format($total_a_regler, 2, ',', '.'); ?> &euro;
                    </p>
                </td>
            </tr>
        </table>
        <div style="height:0.1px; background-color:white; margin: 0 20pt;"></div>
        <table style="width: 100%; border:0; color:white; padding: 5pt 20pt; " id="banque">
            <tr>
                <td style="width: 33%; font-size: 9pt; color: #FFF" rowspan="2">CAISSE D'ÉPARGNE<br>GÉRARDMER</td>
                <td style="width: 7%" class="light">Banque</td>
                <td style="width: 7%" class="light">Guichet</td>
                <td style="width: 10%" class="light">Compte</td>
                <td style="width: 5%" class="light">Clé</td>
                <td style="border-left: 1px solid white; padding-left: 15px;" rowspan="2">
                    <p style="margin:0; margin-bottom:5px;"><span class="light">IBAN: </span>FR76 1513 5005 0008 0058
                        0658 540</p>
                    <p style="margin:0;"><span class="light">BIC: </span>CEPAFRPP513</p>
                </td>
            </tr>
            <tr>
                <td>15135</td>
                <td>00500</td>
                <td>08005806585</td>
                <td>40</td>

            </tr>
        </table>
        <!-- <div style="width:50%; color: white; position: absolute; padding:20pt; top:0;">
        <h3 style="color: #C6C6C5; text-transform: uppercase; font-size: 6pt; margin:0; margin-bottom: 30px">À l'attention de</h3> 
        <p style="font-size: 13pt; margin:0; margin-bottom:5pt"><?php echo $client; ?></p>
        <p class="light" style="margin:0; margin-bottom:5pt"><?php echo $adresse; ?></p>
        <p class="light" style="margin:0"><?php echo $cp; ?> <?php echo $ville; ?></p>
    </div>
    <div style="width:50%; color: white; position: absolute; padding:20pt; top:0; right:0; text-align:right">
        <p style="margin:0; margin-bottom:5pt">Devis // <?php echo $reference; ?></p>
        <p style="margin:0; margin-bottom:5pt">Date // <?php echo $date; ?></p>
        <p style="margin-top:32pt">Validité de l'offre : 30j.</p>

    </div> -->
        <!-- <div style="height:0.1px; background-color:white; position:absolute; top:70%; width:700px; left:20pt"></div> -->

        <!-- <p style="position:absolute; top:80%; padding-left: 20pt; padding-right: 20pt; color: white; font-size: 6pt" class="light">L’ensemble des informations remises à travers ce document demeurent la propriété exclusive de l’agence Alchimy. Vous n’êtes autorisés en aucun cas à diffuser ou divulguer à un tiers les éléments communiqués sans autorisation écrite de notre agence.</p> -->
    </div>
</page>