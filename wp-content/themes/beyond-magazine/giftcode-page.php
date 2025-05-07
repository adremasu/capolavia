<?php
/**
 * Template Name: Gift code page
 */
global $wp;
$input_code = ctype_alnum($_GET[code]) ? $_GET[code] : null;
$code = new giftcodes();
get_header();
?>
<div class="row" id="kt-main">
    <div class="col-md-12">
        <div id="kt-latest-title" class="h3">
            <p><h2><?php the_title();?></h2></p>
        </div>
        <div class="row">
            <div class="col-md-12">

            <?php
                    if ($input_code){

                        $post_id = $code->get_postid_by_code($input_code);
                    }
                    if ($post_id && $input_code){
                        ?>
                        <div class="panel panel-success">
                            
                            <div class="panel-heading">
                                Il giftcode "<b><?php echo $input_code;?></b>" ha ancora un valore residuo di <b>
                                <?php  echo $code->get_residual_value($post_id); ?>&euro;</b>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-md-4 col-lg-4">
                                        <h2>Lista delle spese</h2>
                                        <ul class="list-group">
                                        <?php
                                        $shoppings = $code->get_shoppings_by_code($post_id);
                                        echo '<li class="list-group-item active">Importo iniziale <span class="badge">'.get_post_meta($post_id, 'value', true).'&euro;</span></li>';
                                        foreach($shoppings as $shopping){
                                            $originalDate = $shopping['date'];
                                            $newDate = date("d-m-Y", strtotime($originalDate));
                                            echo '<li class="list-group-item">'.$newDate.' <span class="badge">'.$shopping['value'].'&euro;</span></li>';
                                        }
                                        echo '<li class="list-group-item active">Importo residuo <span class="badge">'.$code->get_residual_value($post_id).'&euro;</span></li>'
                                        ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php

                    } else {
                        ?>
                        <div class="panel panel-danger">
                        <div class="panel-heading">Giftcode non riconosciuto</div>
                            <div class="panel-body"> 
                                Prova con un altro giftcode.
                            </div>
                        </div>
                    <?php                        
                    }
                    

                ?>
                
                <?php
                    the_content();
                ?>
                <form action="<?php echo home_url( $wp->request) ?>"  method="get">
                <div class="form-group">
                    <?php 
                    if (!$input_code){ ?>
                        <label for="exampleInputPassword1">Inserisci il tuo giftcode per controllare il suo valore</label>
                        <?php
                    }
                    else { ?>
                        <label for="exampleInputPassword1">Vuoi controllare un altro giftcode?</label>
                        <?php
                    }
                    ?>

                    <input 
                        type="text" 
                        class="form-control" 
                        id="exampleInputPassword1" 
                        placeholder="Inserisci qui il tuo codice" 
                        name="code"
                    />
                </div>
                <button type="submit" class="btn btn-primary">Controlla</button>
                </form>

            </div>
        </div>
    </div>


</div>

<?php

get_footer();

?>