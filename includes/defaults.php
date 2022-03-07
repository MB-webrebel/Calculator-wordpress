<?php
/**
 * This file maintains the default settings for the plugin.
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */

return array(
    
    // Calculator settings
	'compounding_period'        => 12,
	'currency'                  => '$',
	'currency_code'             => null,
	'currency_format'           => '{currency}{amount} {code}',
    'number_format'             => 6,
    'minimum_total_amount'      => 0,
	'down_payment_visible'      => 1,
	'total_amount_value'        => 300000.00,
	'down_payment_value'        => 60000.00,
	'interest_rate_value'       => 5.00,
	'amortization_period_value' => 30,
    'zero_percent_interest'     => 0,
    'amortization_period_units' => 0,
    'payment_period'            => 12,
    
    // CSS
	'theme'                     => 'light',
	'select_style'              => 1,
	'select_pointer'            => null,
	'css_layout'                => 1,
    
    // Results
	'popup '                    => 2,
	'summary'                   => 1,
	'summary_interest'          => 1,
	'summary_downpayment'       => 1,
    
    // Labels
	'total_amount_label'        => __( 'Total Amount ($)', 'responsive-mortgage-calculator' ),
	'down_payment_label'        => __( 'Down Payment ($)', 'responsive-mortgage-calculator' ),
	'interest_rate_label'       => __( 'Interest Rate (%)', 'responsive-mortgage-calculator' ),
	'amortization_period_label' => __( 'Amortization Period (years)', 'responsive-mortgage-calculator' ),
	'payment_period_label'      => __( 'Payment Period', 'responsive-mortgage-calculator' ),
	'submit_label'              => __( 'Calculate', 'responsive-mortgage-calculator' ),
    
    // Classes
	'total_amount_class'        => null,
	'down_payment_class'        => null,
	'interest_rate_class'       => null,
	'amortization_period_class' => null,
	'payment_period_class'      => null,
	'submit_class'              => null,
);
