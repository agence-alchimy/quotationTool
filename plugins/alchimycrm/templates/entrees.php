<page backtop="30mm" backbottom="20mm" backleft="29mm" backright="29mm" >
    <page_header>
        <div class="header">
            <hr style="border-width:0.1pt; padding-top:10pt; " />
            <table style="width: 100%; border: 0; padding-top: 3mm;">
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
<div>
    <?php 
    foreach($entrees as $entry):

    ?>
        <?php if(empty($prestations)):?>
            <table style=" border: 0;">
                <tr>
                    <td >
                        <h2 style=""><?php echo $entry['titre']; ?></h2>
                    </td>
                </tr>
            </table>
        <?php endif;?>
        <?php
        $i++;
        $prestations = $entry['prestations'];
        if(!empty($prestations)):
        $npresta = count($prestations);
        $j = 0;
        
        foreach($prestations as $prestation): $j++;
        ?>
        <nobreak>
        <?php if($j <= 0): ?>
        <table style=" border: 0;">
                <tr>
                    <td>
                        <h2 style=""><?php echo $entry['titre']; ?></h2>
                    </td>
                </tr>
            </table>
        <?php endif;?>
        
       
        <table class="entry"  style="width: 500px; border: 0; padding-top: 30px">
            <tr>
                <td >
                    <h3><?php echo wordwrap($prestation['titre'], 40, '<br/>', true); ?></h3>
                </td>
                <td >
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
                <td style="text-align: right;">
                    <?php if(empty($prestation['tarif'])) : ?>
                    <h3>OFFERT</h3>
                    <?php else: ?>
                    <h3><?php echo str_replace(',00', '',number_format($n*$prestation['tarif'], 2, ',', '.')); ?> &euro; HT</h3>
                    <?php endif; ?>
                </td>
            </tr>
            <tr >
                <td><div class="desc"><?php echo addSpanToList($prestation['description']); ?></div></td>
            </tr>
        </table>
        </nobreak>
        
        <?php
        if( $prestation['pagebreak'] == '1'): ?>
        
        <?php endif; ?>
        <?php
        if($j < $npresta) echo '<hr style="border:0; border-top: 0.2pt solid #999; margin-top:40px;"/>';
        endforeach; endif;
        ?>
    <?php
    endforeach;
    
    ?>
</div>
</page>