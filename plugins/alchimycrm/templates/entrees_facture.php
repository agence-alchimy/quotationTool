<page backtop="30mm" backbottom="20mm" backleft="24mm" backright="21mm">
    <page_header>
        <div class="header">
            <hr style="border-width:0.5px; margin-top:8mm; " />
            <table style="width: 100%; border: 0; margin-top: 3mm;">
                <tr>
                    <td style="text-align: left;    width: 23%"><img src="medias/logo.png" alt="" width="80"></td>
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
    <table class="contenu" style="padding-top: 30px; padding-left: 0; width:500px; font-size: 9pt;">
            <tr>
                <td >
                    <h2 style="margin-bottom: 50pt; ">Détail des<br>prestations réalisées</h2>
                </td>
            </tr>

            <?php $num = count($services); foreach($services as $k=>$service): ?>
            <tr>
                <td style="width:85%; text-transform: uppercase; padding: 7pt 0; margin: 0;">
                    <?php 
                    echo $service['titre']; 
                    ?>
                    
                </td>
                <td style="width:140px; text-align: right; padding: 7pt 0;">
                <p style="padding: 0; margin: 0;"><em style="text-transform: lowercase; white-space: nowrap;">
                    <?php 
                    $nombre_string = $service['nombre']  > 1 ? $service['nombre']."X" : '';
                    echo $nombre_string;
                    ?>
                </em>
                <?php echo (!empty($service['tarif'])) ?  str_replace(',00', '',number_format($service['tarif'], 2, ',', '.')).' &euro; HT' : 'TEST'; ?></p></td>
            </tr>
            <?php if($k<$num-1): ?>
            <tr>
                <td><hr style="border:0; border-bottom: 0.1pt solid #DDD;"></td>
                <td style="width: 145px"><hr style="border:0; border-bottom: 0.1pt solid #DDD; margin-left: -10px"></td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
            <tr>
                <td><hr style="border:0; border-bottom: 0.1pt solid #DDD;"></td>
                <td style="width: 145px"><hr style="border:0; border-bottom: 0.1pt solid #DDD; margin-left: -10px"></td>
            </tr>
    </table>
</page>