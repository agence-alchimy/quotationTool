<page backtop="20mm" backbottom="20mm" backleft="29mm" backright="29mm">
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
    <page_footer>
        <div class="footer">
            <p class="light" style="font-size: 6pt">L’ensemble des informations remises à travers ce document demeurent la propriété exclusive de l’agence Alchimy. Vous n’êtes autorisés en aucun cas à diffuser ou divulguer à un tiers les éléments communiqués sans autorisation écrite de notre agence.</p>

        </div>
    </page_footer>
    <div class="contenu">
        <div class="entry" style="margin-top: 10pt">
            
            <?php
            $i++;
            $prestations = $entrees;
            $npresta = count($prestations);
            $j = 0;
            foreach($prestations as $prestation):
            ?>
            <table style="width: 100%; border: 0;">
                <tr>
                    <td style="width:60%">
                        <h3>Option : <?php echo $prestation['prestation']->post_title; ?></h3>
                    </td>
                    <td style="width:20%; text-align: right">
                    <?php
                    $n = 1;
                    if($prestation['nombre'] > 1):
                    ?>
                        <h3>x <?php echo $prestation['nombre']; ?></h3>
                        
                    <?php
                    $n = $prestation['nombre'];
                    endif;
                    ?>
                    </td>
                    <td style="width: 20%; text-align: right">
                        <h3><?php echo number_format($n*$prestation['tarif'], 2, ',', '.'); ?> &euro; HT</h3>
                    </td>
                </tr>
            </table>
            <div class="desc"><?php echo addSpanToList($prestation['description']); ?></div>
            <?php
            endforeach;
            ?>
        </div>
    </div>
</page>