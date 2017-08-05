<div class="my_meta_control">
    <?php
    if(!empty($meta['weight'])) $weight_checked = "checked";
    if(!empty($meta['items'])) $items_checked = "checked";
    if(empty($meta['weight_name'])) {
        $weight_name_checked = "kg";
    } else {
        $weight_name_checked = $meta['weight_name'];

    }
    ?>

    <label for="weight">Peso</label>        <input id="weight" <?php echo $weight_checked; ?> type="checkbox" name="_my_meta[weight]" value="1"/><br>
    <label for="items">Pezzi</label>        <input id="weight" <?php echo $items_checked; ?>  type="checkbox" name="_my_meta[items]" value="1"/><br>
    <label for="weight_name">UM peso</label><br>
    kg<input type="radio" <?php if ($weight_name_checked == "kg"){echo "checked";}?> name="_my_meta[weight_name]" value="kg"><br/>
    g <input type="radio" <?php if ($weight_name_checked == "g"){echo "checked";}?> name="_my_meta[weight_name]" value="g"><br>
    <label for="items_name">Nome pezzo</label> <input type="text" name="_my_meta[items_name]"value="<?php if(!empty($meta['items_name'])) echo $meta['items_name']; ?>" >
</div>