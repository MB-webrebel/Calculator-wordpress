<?php

// Mortgage Loan Calculator widget
//
// Copyright (c) 2008-2021 MLCALC.COM
// http://www.mlcalc.com/free-widgets/mortgage-loan-calculator/wordpress.htm
//
// This is an add-on for WordPress
// http://wordpress.org/
//
// **********************************************************************
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// *****************************************************************
/*
Plugin Name: Mortgage Calculator
Plugin URI: https://www.mlcalc.com/free-widgets/mortgage-loan-calculator/wordpress.htm
Description: Powerful mortgage/loan calculator widget for your blog.
Author: mlcalc.com
Version: 1.5.17
Author URI: https://www.mlcalc.com/
*/

/* Function: display_mlcalc_widget
	** This function does the actual display of the widget in the sidebar
	**
	** args: $args (environment variables handled automatically by the hook)
	** returns: nothing
*/

define('MLCALC_URL', plugins_url( '', __FILE__));


function display_mlcalc_widget( $args ) {
	extract( $args );
	$options = get_option( 'widget_mlcalc' );
	$title   = $options['title'];

	echo $before_widget;
	echo $before_title
		 . $title
		 . $after_title;

	display_mlcalc($options);

	echo $after_widget;
}

/* Function: display_mlcalc
	** This function does the actual display of the common part of the widget
	**
	** args: $options - to skip duplicate get_options calls
	** returns: nothing
*/
function display_mlcalc($options = array(), $content = null, $code = "") {
	global $mlcalcURL;
	if(!empty($code) || (!empty($options) && !empty($options[0]) && ($options[0] == 'mlcalc'))){
		$shortcode = true;

		// $atts    ::= array of attributes
		// examples: [mlcalc]
		//           [mlcalc default='mortgage' size='narrow']
		extract( shortcode_atts( array(
			// default parameters
			'default' => 'mortgage_only', // mortgage|loan|mortgage_only|loan_only
			'size' => 'wide', // wide|narrow
			'currency' => 'usd', // usd|eur|gbp|ENTER_YOURS
			'rate' => '4.5', // interest rate
			'price' => '300,000', // purchase price
			'mterm' => '30', // mortgage term
			'down' => '20', // downpayment in %
			'tax' => '3,000', // property tax
			'insurance' => '1,500', // property insurance
			'pmi' => '0.52',
			'amount' => '175,000', // loan amount
			'lterm' => '15', // loan term
			'schedule' => 'year_nc', // month|year|month_nc|year_nc|none (_nc stands for "no choice" for visitor)
			'language' => substr(get_bloginfo('language'), 0, 2), // en|de|es|fr|it|pt|ru
		), $options ) );
		
		$default_calculator = $default;
		$form_size          = ($size == 'narrow' ? 'small' : $size);
		$currency_code      = $currency;
		$interest_rate      = $rate;
		$purchase_price     = $price;
		$mortgage_term      = $mterm;
		$down_payment       = $down;
		$property_tax       = $tax;
		$property_insurance = $insurance;
		$pmi                = $pmi;
		$loan_amount        = $amount;
		$loan_term          = $lterm;
		$amortization       = sanitize_text_field($schedule);
	} else {
		$shortcode = false;

		$options = get_option( 'widget_mlcalc' );
		$title              = empty( $options['title'] ) ? __('Loan Calculator') : $options['title'];
		$default_calculator = empty( $options['default_calculator'] ) ? 'loan' : $options['default_calculator'];
		$form_size          = empty( $options['form_size'] ) ? 'small' : $options['form_size'];
		$currency_code      = empty( $options['currency_code'] ) ? 'usd' : $options['currency_code'];
		$interest_rate      = empty( $options['interest_rate'] ) ? '4.5' : $options['interest_rate'];
		$purchase_price     = empty( $options['purchase_price'] ) ? '300,000' : $options['purchase_price'];
		$mortgage_term      = empty( $options['mortgage_term'] ) ? '30' : $options['mortgage_term'];
		$down_payment       = empty( $options['down_payment'] ) ? '10' : $options['down_payment'];
		$property_tax       = empty( $options['property_tax'] ) ? '3,000' : $options['property_tax'];
		$property_insurance = empty( $options['property_insurance'] ) ? '1,500' : $options['property_insurance'];
		$pmi                = empty( $options['pmi'] ) ? '0.52' : $options['pmi'];
		$loan_amount        = empty( $options['loan_amount'] ) ? '175,000' : $options['loan_amount'];
		$loan_term          = empty( $options['loan_term'] ) ? '15' : $options['loan_term'];
		$amortization       = empty( $options['amortization'] ) ? 'year_nc' : $options['amortization'];
		$language           = empty( $options['language'] ) ? substr(get_bloginfo('language'), 0, 2) : $options['language'];
	};

	if($currency_code == 'eur') $currency_symbol = '&euro;';
	if($currency_code == 'gbp') $currency_symbol = '&pound;';
	if($currency_code == 'usd') $currency_symbol = '$';
	if(empty($currency_symbol)) $currency_symbol = substr(sanitize_text_field($currency_code), 0, 3);

    if (!in_array($language, array('en', 'de', 'es', 'fr', 'it', 'pt', 'ru', 'al'))) {
        $language = 'en';
    }

	$display_mortgage_form = $display_loan_form = $display_loan_only = $display_mortgage_only = $display_loan = $display_mortgage = 'style="display:none"';

	switch ($default_calculator){
		case 'mortgage':
			$display_mortgage = $display_mortgage_form = '';
		break;
		case 'loan_only':
			$display_loan_only = $display_loan_form = '';
		break;
		case 'mortgage_only':
			$display_mortgage_only = $display_mortgage_form = '';
		break;
		case 'loan':
		default:
			$display_loan = $display_loan_form = '';
		break;
	}

	$hidden = '';
	$display_amortization = '';

    if (!in_array($amortization, array('month', 'year', 'month_nc', 'year_nc', 'none'))) {
        $amortization = 'year_nc';
    }

	if ((preg_match('/_nc/i', $amortization)) || $amortization == 'none') {
		$display_amortization = 'style="display:none"';
		$hidden .= '<input type="hidden" name="as" value="' . str_replace('_nc', '', $amortization) . '" />';
	}

	$as_year  = (preg_match('/year/i', $amortization)) ? 'checked="checked"' : '';
	$as_month = (preg_match('/month/i', $amortization)) ? 'checked="checked"' : '';
	
	$WL_DIR_PREFIX = $language."/";
	
	// LOAD SCRIPTS
	wp_enqueue_script( 'mlcalc-widget-script', plugins_url($WL_DIR_PREFIX.'widget-nj.js', __FILE__), array('jquery') );
	
	// LOAD STYLES
	wp_register_style( 'mlcalc-form-small-style', plugins_url('widget-form-small.css', __FILE__) );
	wp_register_style( 'mlcalc-form-style', plugins_url('widget-form.css', __FILE__) );
	if($form_size == 'small'){
		wp_enqueue_style( 'mlcalc-form-small-style' );
	} else {
		wp_enqueue_style( 'mlcalc-form-style' );
	}
	
	if($shortcode) ob_start();
	
	echo "<!-- MLCALC BEGIN -->\r\n";
	echo "<script type=\"text/javascript\"><!--\r\n";
	echo "mlcalc_currency_symbol = '$currency_symbol';\r\n";
	echo "mlcalc_amortization    = '$amortization';\r\n";
	echo "var _mlcalc_preload_img = new Image(312,44);\r\n";
	echo "_mlcalc_preload_img.src='".plugins_url('images/ajax-loader.gif', __FILE__)."';\r\n";
	echo "--></script>\r\n";

	include($WL_DIR_PREFIX."forms.inc.php");
	echo "<!-- MLCALC END -->\r\n";
	if($shortcode){
		$result = ob_get_contents();
		ob_end_clean();
		if(is_null($content)){
			return $result;
		} else {
			return $content . $result;
		}
	}
}

/* Function: mlcalc_control
**
** This function draws the controls form on the widget page and
** saves the settings when the "Save" button is clicked
**
** args: nothing
** returns: nothing
*/

function mlcalc_control() {
	$options = $newoptions = get_option('widget_mlcalc');

	if ( !empty($_POST['mlcalc-submit'] )) {
		$newoptions['title']              = sanitize_title($_POST['mlcalc-title']);
		$newoptions['default_calculator'] = sanitize_text_field($_POST['mlcalc-default_calculator']);
		$newoptions['form_size']          = sanitize_text_field($_POST['mlcalc-form_size']);
		$newoptions['currency_code']      = sanitize_text_field($_POST['mlcalc-currency_code']);
		$newoptions['interest_rate']      = sanitize_text_field($_POST['mlcalc-interest_rate']);
		$newoptions['purchase_price']     = sanitize_text_field($_POST['mlcalc-purchase_price']);
		$newoptions['mortgage_term']      = sanitize_text_field($_POST['mlcalc-mortgage_term']);
		$newoptions['down_payment']       = sanitize_text_field($_POST['mlcalc-down_payment']);
		$newoptions['property_tax']       = sanitize_text_field($_POST['mlcalc-property_tax']);
		$newoptions['property_insurance'] = sanitize_text_field($_POST['mlcalc-property_insurance']);
		$newoptions['pmi']                = sanitize_text_field($_POST['mlcalc-pmi']);
		$newoptions['loan_amount']        = sanitize_text_field($_POST['mlcalc-loan_amount']);
		$newoptions['loan_term']          = sanitize_text_field($_POST['mlcalc-loan_term']);
		$newoptions['amortization']       = sanitize_text_field($_POST['mlcalc-amortization']);
		$newoptions['language']           = sanitize_text_field($_POST['mlcalc-language']);
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_mlcalc', $options);
	}

	$title = esc_attr($options['title']);
	$default_calculator = esc_attr($options['default_calculator']);
	$form_size = esc_attr($options['form_size']);
	$currency_code = esc_attr($options['currency_code']);
	$interest_rate = esc_attr($options['interest_rate']);
	$purchase_price = esc_attr($options['purchase_price']);
	$mortgage_term = esc_attr($options['mortgage_term']);
	$down_payment = esc_attr($options['down_payment']);
	$property_tax = esc_attr($options['property_tax']);
	$property_insurance = esc_attr($options['property_insurance']);
	$pmi = esc_attr($options['pmi']);
	$loan_amount = esc_attr($options['loan_amount']);
	$loan_term = esc_attr($options['loan_term']);
	$amortization = esc_attr($options['amortization']);
	$language = esc_attr($options['language']);
?>
		<p><label for="mlcalc-title"><?php _e('Title:'); ?> <input class="widefat" id="mlcalc-title" name="mlcalc-title" type="text" value="<?php echo $title; ?>" /></label></p>
		<p>
			<label for="mlcalc-default_calculator"><?php _e( 'Calculator:' ); ?>
				<select name="mlcalc-default_calculator" id="mlcalc-default_calculator" class="widefat">
					<option value="loan"<?php selected( $options['default_calculator'], 'loan' ); ?>><?php _e('Loan by Default'); ?></option>
					<option value="loan_only"<?php selected( $options['default_calculator'], 'loan_only' ); ?>><?php _e('Loan Only'); ?></option>
					<option value="mortgage"<?php selected( $options['default_calculator'], 'mortgage' ); ?>><?php _e('Mortgage by Default'); ?></option>
					<option value="mortgage_only"<?php selected( $options['default_calculator'], 'mortgage_only' ); ?>><?php _e('Mortgage Only'); ?></option>
				</select>
			</label>
		</p>
		<p>
			<label for="mlcalc-form_size"><?php _e( 'Form size:' ); ?>
				<select name="mlcalc-form_size" id="mlcalc-form_size" class="widefat">
					<option value="small"<?php selected( $options['form_size'], 'small' ); ?>><?php _e('Narrow (width = 150px)'); ?></option>
					<option value="big"<?php selected( $options['form_size'], 'big' ); ?>><?php _e('Wide (width = 300px)'); ?></option>
				</select>
			</label>
		</p>
		<p>
			<label for="mlcalc-amortization"><?php _e( 'Amortization:' ); ?>
				<select name="mlcalc-amortization" id="mlcalc-amortization" class="widefat">
					<option value="year_nc"<?php selected( $options['amortization'], 'year_nc' ); ?>><?php _e('Show by year (no selector)'); ?></option>
					<option value="month_nc"<?php selected( $options['amortization'], 'month_nc' ); ?>><?php _e('Show by month (no selector)'); ?></option>
					<option value="year"<?php selected( $options['amortization'], 'year' ); ?>><?php _e('Show by year'); ?></option>
					<option value="month"<?php selected( $options['amortization'], 'month' ); ?>><?php _e('Show by month'); ?></option>
					<option value="none"<?php selected( $options['amortization'], 'none' ); ?>><?php _e('Don\'t show'); ?></option>
				</select>
			</label>
		</p>
		<p>
			<label for="mlcalc-language"><?php _e( 'Language:' ); ?>
				<select name="mlcalc-language" id="mlcalc-language" class="widefat">
					<option value="en"<?php selected( $options['language'], 'en' ); ?>>English</option>
					<option value="de"<?php selected( $options['language'], 'de' ); ?>>Deutsch</option>
					<option value="es"<?php selected( $options['language'], 'es' ); ?>>Espa&#241;ol</option>
					<option value="fr"<?php selected( $options['language'], 'fr' ); ?>>Fran&#231;ais</option>
					<option value="it"<?php selected( $options['language'], 'it' ); ?>>Italiana</option>
					<option value="pt"<?php selected( $options['language'], 'pt' ); ?>>Portugu&#234;s</option>
					<option value="ru"<?php selected( $options['language'], 'ru' ); ?>>&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;</option>
				</select>
			</label>
		</p>
		<p>
			<label for="mlcalc-currency_code"><?php _e( 'Currency symbol:' ); ?>
				<select name="mlcalc-currency_code" id="mlcalc-currency_code" class="widefat">
					<option value="usd"<?php selected( $options['currency_code'], 'usd' ); ?>><?php _e('USD ($)'); ?></option>
					<option value="eur"<?php selected( $options['currency_code'], 'eur' ); ?>><?php _e('EUR (&euro;)'); ?></option>
					<option value="gbp"<?php selected( $options['currency_code'], 'gbp' ); ?>><?php _e('GBP (&pound;)'); ?></option>
					<option value="R"<?php selected( $options['currency_code'], 'R' ); ?>><?php _e('R'); ?></option>
					<option value="Rp"<?php selected( $options['currency_code'], 'Rp' ); ?>><?php _e('Rp'); ?></option>
					<option value="AED"<?php selected( $options['currency_code'], 'AED' ); ?>><?php _e('AED'); ?></option>
					<option value="Php"<?php selected( $options['currency_code'], 'Php' ); ?>><?php _e('Php'); ?></option>
				</select>
			</label>
		</p>
		<p><a href="https://www.mlcalc.com/" onclick="(typeof jQuery != 'undefined') ? jQuery('.mlcalc_default_values').show() : document.getElementById('mlcalc_default_values').style.display = 'inline'; this.style.display = 'none'; return false;">Change default values...</a></p>
		<span style="display:none" id="mlcalc_default_values" class="mlcalc_default_values">
		<p><label for="mlcalc-purchase_price"><?php _e( 'Purchase price' ); ?>: <br /> <input style="width: 65px; text-align: center;" type="text" value="<?php echo $purchase_price; ?>" name="mlcalc-purchase_price" id="mlcalc-purchase_price" /> $</label></p>
		<p><label for="mlcalc-down_payment"><?php _e( 'Down payment' ); ?> <small>(<a href="https://www.mlcalc.com/" onclick="(typeof jQuery != 'undefined') ? jQuery('.mlcalc-down_payment').val('null') : document.getElementById('mlcalc-down_payment').value='null'; return false;">hide this field</a>)</small>: <br /> <input style="width: 35px; text-align: center;" type="text" value="<?php echo $down_payment; ?>" name="mlcalc-down_payment" id="mlcalc-down_payment" class="mlcalc-down_payment" /> %</label></p>
		<p><label for="mlcalc-mortgage_term"><?php _e( 'Mortgage term' ); ?>: <br /> <input style="width: 35px; text-align: center;" type="text" value="<?php echo $mortgage_term; ?>" name="mlcalc-mortgage_term" id="mlcalc-mortgage_term" /> years</label></p>
		<p><label for="mlcalc-interest_rate"><?php _e( 'Interest rate' ); ?>: <br /> <input style="width: 35px; text-align: center;" type="text" value="<?php echo $interest_rate; ?>" name="mlcalc-interest_rate" id="mlcalc-interest_rate" /> %</label></p>
		<p><label for="mlcalc-property_tax"><?php _e( 'Property tax' ); ?> <small>(<a href="https://www.mlcalc.com/" onclick="(typeof jQuery != 'undefined') ? jQuery('.mlcalc-property_tax').val('null') : document.getElementById('mlcalc-property_tax').value='null'; return false;">hide this field</a>)</small>: <br /> <input style="width: 65px; text-align: center;" type="text" value="<?php echo $property_tax; ?>" name="mlcalc-property_tax" id="mlcalc-property_tax" class="mlcalc-property_tax" /> $ per year</label></p>
		<p><label for="mlcalc-property_insurance"><?php _e( 'Property insurance' ); ?> <small>(<a href="https://www.mlcalc.com/" onclick="(typeof jQuery != 'undefined') ? jQuery('.mlcalc-property_insurance').val('null') : document.getElementById('mlcalc-property_insurance').value='null'; return false;">hide this field</a>)</small>: <br /> <input style="width: 65px; text-align: center;" type="text" value="<?php echo $property_insurance; ?>" name="mlcalc-property_insurance" id="mlcalc-property_insurance" class="mlcalc-property_insurance" /> $ per year</label></p>
		<p><label for="mlcalc-pmi"><?php _e( 'PMI' ); ?> <small>(<a href="https://www.mlcalc.com/" onclick="(typeof jQuery != 'undefined') ? jQuery('.mlcalc-pmi').val('null') : document.getElementById('mlcalc-pmi').value='null'; return false;">hide this field</a>)</small>: <br /> <input style="width: 35px; text-align: center;" type="text" value="<?php echo $pmi; ?>" name="mlcalc-pmi" id="mlcalc-pmi" class="mlcalc-pmi" /> %</label></p>
		<p><label for="mlcalc-loan_amount"><?php _e( 'Loan amount' ); ?>: <br /> <input style="width: 65px; text-align: center;" type="text" value="<?php echo $loan_amount; ?>" name="mlcalc-loan_amount" id="mlcalc-loan_amount" /> $</label></p>
		<p><label for="mlcalc-loan_term"><?php _e( 'Loan term' ); ?>: <br /> <input style="width: 35px; text-align: center;" type="text" value="<?php echo $loan_term; ?>" name="mlcalc-loan_term" id="mlcalc-loan_term" /> years</label></p>
		</span>

		<input type="hidden" id="mlcalc-submit" name="mlcalc-submit" value="1" />
<?php
}

/* Function: mlcalc_register
**
** Registers the MLCALC widget with the widget page
**
** args: none
** returns: nothing
*/

function mlcalc_register() {
	$widget_ops = array('classname' => 'widget_mlcalc', 'description' => __('Mortgage and loan calculator'));
	$name = __('Mortgage Calculator');

	wp_register_sidebar_widget( 'mlcalc', $name, 'display_mlcalc_widget', $widget_ops );
	wp_register_widget_control( 'mlcalc', $name, 'mlcalc_control' );
}

// This is important
add_action( 'widgets_init', 'mlcalc_register' );
add_shortcode( 'mlcalc', 'display_mlcalc' );


?>