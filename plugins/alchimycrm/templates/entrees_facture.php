<page backtop="20mm" backbottom="20mm" backleft="15mm" backright="15mm">
    <page_header>
        <div class="header">
            <hr style="border-width:0.1pt; margin-top:10pt; " />
            <table style="width: 100%; border: 0; margin-top: 3mm;">
                <tr>
                    <td style="text-align: left;    width: 23%"><img src="medias/logo.png" alt="" width="70"></td>
                    <td style="text-align: left;    width: 44%" class="infos">Facture // <strong><?php echo $reference; ?></strong><br />Date // <strong><?php echo $date; ?></strong></td>
                    <td style="text-align: right;    width: 33%" class="nums">page [[page_cu]]/[[page_nb]]</td>
                </tr>
            </table>
        </div>
    </page_header>
    <page_footer>
        <div class="footer">
            <p class="light" style="font-size: 6pt; text-align: center"><img src="medias/logoA.png" alt="" width="25"></p>
        </div>
    </page_footer>
    <div class="contenu">
        <table class="entry"  style="width: 500px; table-layout: atuo; border: 0; padding-bottom: 10px">
                <tr >
                    <td>
                        <h2 style="margin-bottom: 50pt; ">Détails des<br>prestations réalisées</h2>
                    </td>
                </tr>

                <?php $num = count($services); foreach($services as $k=>$service): ?>
                <tr style="border-bottom: 1px solid #a1a1a0; margin-bottom: 10px;">
                    <td style=" width: 20%">
                        <h3 ><?php echo wordwrap($service['titre'], 40, '<br/>', true); ?></h3>
                    </td>
                    <td  style="width: 10%;">
                    <?php
                    $n = 1;
                    if($service['nombre'] > 1):
                    ?>
                        <h3>x <?php echo $service['nombre']; ?></h3>
                    <?php
                    $n = $service['nombre'];
                    endif;
                    ?>
                    </td>
                    <td style="width: 23%">
                        <?php if(empty($service['tarif'])) : ?>
                        <h3 style="text-align: right; width: 100%">OFFERT</h3>
                        <?php else: ?>
                        <h3 style="text-align: right; ; width: 100%"><?php echo str_replace(',00', '',number_format($n*$service['tarif'], 2, ',', '.')); ?> &euro; HT</h3>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if($k<$num-1): ?>
                    <tr style="width:100%;">
                        <td colspan="3">
                            <div style="height: 1px; background: #a1a1a0; margin-bottom: 10px;"></div>
                        </td>
                        <td style="width:33%;" colspan="1">
                            <div style="height: 1px; background: #a1a1a0; margin-bottom: 10px; margin-left: -10px"></div>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php endforeach; ?>
        </table>
         
    </div>
</page>