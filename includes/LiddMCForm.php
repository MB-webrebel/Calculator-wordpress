<?php

defined('ABSPATH') or die("...");

/**
 * A class to define the mortgage calculator form
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */
class LiddMCForm
{
	/**
	 * Store the settings
	 * @var array
	 */
	private $options;
	
	/**
	 * Store the input objects
	 * @var array
	 */
	private $inputs;
	
	/**
	 * Store the name and id
	 * @var string
	 */
	private $name;
    
    /**
     * Store the submission processor
     * @var object
     */
    private $processor;
	
	/**
	 * Constructor.
	 *
	 * Sets the name/id of the form and stores the options.
	 *
	 * @param  string  $name     The name and id of the form.
	 * @param  array   $options  The calculator settings.
	 */
	public function __construct( $name, $options )
	{
		$this->name = $name;
		$this->options = $options;
        
        $this->set_processor();
	}
	
    /**
     * Process submissions
     */
    private function set_processor()
    {
        include LIDD_MC_ROOT . 'includes/LiddMCProcessor.php';
        $this->processor = new LiddMCProcessor( $this->options['compounding_period'] );
    }
    
	/**
	 * Return the form.
	 *
	 * @return  string   The HTML to display the form.
	 */
	public function getForm()
	{
		// Store the options locally
		$options = $this->options;
		
		// Create the inputs
		$factory = new LiddMCInputFactory( $options['theme'], $options['css_layout'], $options['select_style'], $options['select_pointer'] );
		
		// Total Amount
		$ta = $factory->newInput( 'text', 'lidd_mc_total_amount' );
		$ta->setLabel( $options['total_amount_label'] );
		$ta->setPlaceholder( $options['currency'] );
		$ta->setClass( $options['total_amount_class'] );
		
		// Down payment
		if ( $options['down_payment_visible'] != false && $options['down_payment_visible'] != "false" ) {
			$dp = $factory->newInput( 'text', 'lidd_mc_down_payment' );
			$dp->setLabel( $options['down_payment_label'] );
			$dp->setPlaceholder( $options['currency'] );
			$dp->setClass( $options['down_payment_class'] );
		} else {
			$dp = $factory->newInput( 'hidden', 'lidd_mc_down_payment' );
			$dp->setValue( 0 );
			$options['down_payment_value'] = 0;
		}
	
		// Interest rate
		$ir = $factory->newInput( 'text', 'lidd_mc_interest_rate' );
		$ir->setLabel( $options['interest_rate_label'] );
		$ir->setPlaceholder( '%' );
		$ir->setClass( $options['interest_rate_class'] );
	
		// Amortization period
		$ap = $factory->newInput( 'text', 'lidd_mc_amortization_period' );
		$ap->setLabel( $options['amortization_period_label'] );
        if ( isset( $options['amortization_period_units'] ) ) {
            switch ( $options['amortization_period_units'] ) {
                case 1:
            		$ap->setPlaceholder( __( 'months', 'responsive-mortgage-calculator' ) );
                    break;
                case 0:
                default:
            		$ap->setPlaceholder( __( 'years', 'responsive-mortgage-calculator' ) );
                    break;
                    
            }
        } else {
    		$ap->setPlaceholder( __( 'years', 'responsive-mortgage-calculator' ) );
        }
		$ap->setClass( $options['amortization_period_class'] );
	
		// Payment period
		if ( in_array( $options['payment_period'], array( 1, 2, 4, 12, 26, 52 ) ) ) {
			$pp = $factory->newInput( 'hidden', 'lidd_mc_payment_period' );
			$pp->setValue( $options['payment_period'] );
		} else {
            
			$pp = $factory->newInput( 'select', 'lidd_mc_payment_period' );
			$pp->setLabel( $options['payment_period_label'] );
			$pp->setClass( $options['payment_period_class'] );
            
            // Create the options array
            $pp_options = array();
            
            // Create a reference array for allowed payment periods
            $allowed_payment_periods = array(
				1  => __( 'Yearly', 'responsive-mortgage-calculator' ),
				2  => __( 'Semi-Annually', 'responsive-mortgage-calculator' ),
				4  => __( 'Quarterly', 'responsive-mortgage-calculator' ),
				12 => __( 'Monthly', 'responsive-mortgage-calculator' ),
				26 => __( 'Bi-Weekly', 'responsive-mortgage-calculator' ),
				52 => __( 'Weekly', 'responsive-mortgage-calculator' )
            );
            
            // Loop over allowed payment periods to check for settings
            foreach ( $allowed_payment_periods as $app => $app_name ) {
                
                // Temporary variable to store the option name for the payment period
                $app_option_name = 'payment_period_option_' . $app;
                
                // Check if this payment period option is set
                if ( isset( $options[ $app_option_name ] ) && $options[ $app_option_name ] ) {
                    $pp_options[ $app ] = $app_name;
                }
            }
            
            // Make sure payment periods are set
            if ( empty( $pp_options ) ) {
                
                // Default payment periods
                $pp_options = array(
    				12 => __( 'Monthly', 'responsive-mortgage-calculator' ),
    				26 => __( 'Bi-Weekly', 'responsive-mortgage-calculator' ),
    				52 => __( 'Weekly', 'responsive-mortgage-calculator' )
                );
            }
            
            // Pass the payment periods to the payment period input
			$pp->setOptions( $pp_options );
		}
	
		// Submit button
		$sub = $factory->newInput( 'submit', 'lidd_mc_submit' );
		$sub->setValue( $options['submit_label'] );
		$sub->setClass( $options['submit_class'] );
        
        // Set submitted data submission
        if ( $this->processor->has_submission() ) {
            
            $ta->setValue( $this->processor->get( 'total_amount' ) );
            $dp->setValue( $this->processor->get( 'down_payment' ) );
            $ir->setValue( $this->processor->get( 'interest_rate' ) );
            $ap->setValue( $this->processor->get( 'amortization_period' ) );
            $pp->setValue( $this->processor->get( 'payment_period' ) );
            
            if ( $this->processor->has_error() ) {
                
                $localization = rmc_get_localization();
                $errors = $this->processor->get_errors();
                
                ( isset( $errors['total_amount'] ) ) && $ta->setError( $localization['ta_error'] );
                ( isset( $errors['down_payment'] ) ) && $dp->setError( $localization['dp_error'] );
                ( isset( $errors['interest_rate'] ) ) && $ir->setError( $localization['ir_error'] );
                ( isset( $errors['amortization_period'] ) ) && $ap->setError( $localization['ap_error'] );
            }
        }
        // Set options
        else {
            
            isset( $options['total_amount_value'] ) && $ta->setValue( $options['total_amount_value'] );
            isset( $options['down_payment_value'] ) && $dp->setValue( $options['down_payment_value'] );
            isset( $options['interest_rate_value'] ) && $ir->setValue( $options['interest_rate_value'] );
            isset( $options['amortization_period_value'] ) && $ap->setValue( $options['amortization_period_value'] );
            
        }
        
    	// Create a display area for results.
    	$details = new LiddMCDetails( $options, $this->processor );
		
		// Build the form
        $protocol = ( is_ssl() ) ? 'https://' : 'http://';
		$form = "<form action=\"$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]#" . esc_attr( $this->name ) . "\" id=\"" . esc_attr( $this->name ) . "\" class=\"" . esc_attr( $this->name ) . "\" method=\"post\">";

		$form .= $ta->getInput();
		$form .= $dp->getInput();
		$form .= $ir->getInput();
		$form .= $ap->getInput();
		$form .= $pp->getInput();
		$form .= $sub->getInput();
		
		$form .= '</form>';
		$form .= '<form action="https://www.mlcalc.com/" method="post" id="lidd_mc_mlc_form" target="MLCalcFrame">';
		$form .= '<input type="hidden" name="ml" value="mortgage" />';
		$form .= '<input type="hidden" name="cl" value="true" />';
		$form .= '<input type="hidden" name="wg" value="widget" />';
		$form .= '<input type="hidden" name="wt" value="rmc" />';
		$form .= '<input type="hidden" name="cr" value="'.(!empty($options['currency_code']) ? $options['currency_code'] : 'usd').'" />';
		$form .= '<input type="hidden" name="cr" value="'.(empty($options['currency_code']) ? $options['currency_code'] : 'usd').'" />';
		$form .= '<input type="hidden" name="wl" value="en" />';
		$form .= '<input type="hidden" name="ma" value="300000.00" />';
		$form .= '<input type="hidden" name="dp" value="60000.00" />';
		$form .= '<input type="hidden" name="mt" value="30" />';
		$form .= '<input type="hidden" name="ir" value="5.00" />';
		$form .= '<input type="hidden" name="pt" value="0" />';
		$form .= '<input type="hidden" name="pi" value="0" />';
		$form .= '<input type="hidden" name="mi" value="0" />';
		$form .= '</form>';

        $form .= $details->getDetails();
		
		return $form;
	}
		
}
