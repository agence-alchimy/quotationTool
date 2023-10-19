<page backtop="20mm" backbottom="10mm" backleft="10mm" backright="10mm">
    <page_header>
        <div class="header">
            <hr style="border-width:0.1pt; margin-top:10pt; " />
            <table style="width: 100%; border: 0; margin-top: 3mm;">
                <tr>
                    <td style="text-align: left;    width: 23%"><img src="medias/logo.png" alt="" width="70"></td>
                    <td style="text-align: left;    width: 44%" class="infos">Devis // <strong><?php echo $reference; ?></strong><br />Date // <strong><?php echo $date; ?></strong></td>
                    <td style="text-align: right;    width: 33%" class="nums">page [[page_cu]]/[[page_nb]]</td>
                    
                </tr>
            </table>
        </div>
    </page_header>
    <div style="width:100%;  position:absolute; bottom:0; ">
        <p style="text-align: center; color:#C6C6C5; font-size: 6pt" class="light">LA SIGNATURE DE CE DEVIS VAUT POUR ACCEPTATION DES CONDITIONS GÉNÉRALES DE VENTES JOINTES.</p>
        <div style="background-color:#f7f7f7; margin:auto; margin-bottom: 25px;">
            <table style="width: 100%; border:0; padding: 7mm; border-collapse: collapse;">
                <tr>
                    <td style="width:55%">
                        <p style="font-size: 8pt; margin-bottom: 0; margin-top:0;">DATE ET SIGNATURE :</p>
                        <p style="font-size: 8pt; margin-top: 3pt;" class="light">Accompagné de la mention<br />"lu et approuvé"</p>
                        <p style="font-size: 7pt; margin-top: 22mm; margin-bottom:0;"><strong>Conditions de réglement</strong> <span class="light">: 30 jours</span><br /><strong>Mode de réglement</strong> <span class="light">: Chèque ou Virement bancaire</span></p>
                    </td>
                    <td style="width:25%; vertical-align:middle; border: 0;">
                        <p style="margin:0; margin-bottom: 5pt;" class="light">Total HT :</p>
                        <?php if($total_remise > 0) : ?>
                            <p style="margin:0; margin-bottom: 5pt;" class="light">Remise :</p>
                            <p style="margin:0; margin-bottom: 5pt;" class="">Total après remise :</p>
                        <?php endif; ?>
                        <p style="margin:0; margin-bottom: 5pt; " class="light">TVA (20%) :</p>
                        <p style="margin:0; margin-bottom: 5pt;" class="">Total TTC :</p>
                        <br />
                        <p style="margin:0; margin-bottom: 5pt;" class="">Acompte (50%) :</p>
                    </td>
                    <td style="width: 20%; vertical-align:middle; text-align: right; border: 0;">
                        <p style="margin:0; margin-bottom: 5pt;" class="light"><?php echo str_replace(',00', '',number_format($total_ht + $total_remise, 2, ',', '.')); ?> &euro;</p>
                        <?php if($total_remise > 0) : ?>
                            <p style="margin:0; margin-bottom: 5pt;" class="light"><?php echo str_replace(',00', '',number_format($total_remise, 2, ',', '.')); ?> &euro;</p>
                            <p style="margin:0; margin-bottom: 5pt;" class=""><?php echo str_replace(',00', '',number_format($total_ht, 2, ',', '.')); ?> &euro;</p>
                        <?php endif; ?>
                        <p style="margin:0; margin-bottom: 5pt;" class="light"><?php echo str_replace(',00', '',number_format($total_ht*TVA, 2, ',', '.')); ?> &euro;</p>
                        <p style="margin:0; margin-bottom: 5pt;" class="light"><?php echo str_replace(',00', '',number_format($total_ttc, 2, ',', '.')); ?> &euro;</p>
                        <br />
                        <p style="margin:0; margin-bottom: 5pt;" class="light"><?php echo str_replace(',00', '',number_format($total_ttc/2, 2, ',', '.')); ?> &euro;</p>
                        
                    </td>
                </tr>
            </table>
        </div>
        <table style="width: 100%; border:0; " id="banque">
            <tr>
                <td style="width: 33%; font-size: 9pt; color: #878786" rowspan="2">CAISSE D'ÉPARGNE<br>GÉRARDMER</td>
                <td style="width: 7%" class="light">Banque</td>
                <td style="width: 7%" class="light">Guichet</td>
                <td style="width: 10%" class="light">Compte</td>
                <td style="width: 5%" class="light">Clé</td>
                <td style="border-left: 1px solid black; padding-left: 15px;" rowspan="2" ><p style="margin:0; margin-bottom:5px;"><span class="light">IBAN: </span>FR76 1513 5005 0008 0058 0658 540</p><p style="margin:0;"><span class="light">BIC: </span>CMCIFRPP</p></td>
            </tr>
            <tr>
                <td>15135</td>
                <td>00500</td>
                <td>08005806585</td>
                <td>40</td>
                
            </tr>
        </table>
    </div>
</page>