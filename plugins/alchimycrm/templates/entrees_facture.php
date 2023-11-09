<page backtop="30mm" backbottom="20mm" backleft="29mm" backright="29mm">
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
    <table class="contenu" style="width:100%; font-size: 9pt">
            <tr>
                <td>
                    <h2 style="margin-bottom: 50pt; ">Détails des<br>prestations réalisées</h2>
                </td>
            </tr>

            <?php $num = count($services); foreach($services as $k=>$service): ?>
            <tr>
                <td style="width:85%; text-transform: uppercase;padding: 10pt 0; ">
                    <?php 
                    echo $service['titre']; 
                    ?>
                    <em style="text-transform: lowercase; white-space: nowrap;">
                        <?php 
                        $nombre_string = $service['nombre']  > 1 ? " ( x".$service['nombre']." )" : '';
                        echo $nombre_string;
                        ?>
                    </em>
                </td>
                <td style="width:15%; text-align: right; padding: 10pt 0 "><?php 
                if(is_numeric($service['tarif'])){
                    echo str_replace(',00', '',number_format($n*$prestation['tarif'], 2, ',', '.')) . "&euro; HT"; 
                }
                else{
                    echo $service['tarif']; 
                }
                ?></td>
            </tr>
            <?php if($k<$num-1): ?>
            <tr>
                <td colspan="2"><hr style="border:0; border-bottom: 0.1pt solid #999;"></td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
            <tr style="width:100%;">
                <td colspan="2"><hr style="border:0; border-bottom: 0.1pt solid #999;"></td>
            </tr>
    </table>
</page>