<?php
function rmc_get_localization() {

    $options = new LiddMCOptions( LIDD_MC_OPTIONS );
    
	// HTML wrapper on return values 
	$bs = '<b class="lidd_mc_b">';
	$be = '</b>';
    
    $min_ta = $options->getOption( 'minimum_total_amount' );
    
    if ( $min_ta ) {
        $min_ta_error = sprintf( __( 'Please enter a total amount greater than $%s.', 'responsive-mortgage-calculator' ), $min_ta );
    }
    else {
        $min_ta_error = __( 'Please enter the total amount. ', 'responsive-mortgage-calculator' );
    }
    
    $localization = array(
        
        // Settings
        'currency'              => $options->getOption( 'currency' ),
        'currency_code'         => $options->getOption( 'currency_code' ),
		'currency_format'       => $bs . $options->getOption( 'currency_format' ) . $be,
        'number_format'         => $options->getOption( 'number_format' ),
        'compounding_period'    => $options->getOption( 'compounding_period' ),
        'minimum_total_amount'  => $min_ta,
        'zero_percent_interest' => $options->getOption( 'zero_percent_interest' ),
        'amortization_period_units' => $options->getOption( 'amortization_period_units' ),
        'summary'               => $options->getOption( 'summary' ),
        'summary_interest'      => $options->getOption( 'summary_interest' ),
        'summary_downpayment'   => $options->getOption( 'summary_downpayment' ),
        'popup'                 => $options->getOption( 'popup' ),

        // Error messages
		'ta_error'              => $min_ta_error,
		'dp_error'              => __( 'Please enter a down payment amount or leave blank.', 'responsive-mortgage-calculator' ),
		'ir_error'              => __( 'Please enter an interest rate.', 'responsive-mortgage-calculator' ),
		'ap_error'              => __( 'Please enter an amortization period.', 'responsive-mortgage-calculator' ),
        
        // Time period wording
		'weekly'                => __( 'Weekly', 'responsive-mortgage-calculator' ),
		'biweekly'              => __( 'Bi-Weekly', 'responsive-mortgage-calculator' ),
		'monthly'               => __( 'Monthly', 'responsive-mortgage-calculator' ),
		'quarterly'             => __( 'Quarterly', 'responsive-mortgage-calculator' ),
		'semiannual'            => __( 'Semi-Annual', 'responsive-mortgage-calculator' ),
		'yearly'                => __( 'Yearly', 'responsive-mortgage-calculator' ),
		'weekly_payment'        => __( 'Weekly Payment', 'responsive-mortgage-calculator' ),
		'biweekly_payment'      => __( 'Bi-Weekly Payment', 'responsive-mortgage-calculator' ),
		'monthly_payment'       => __( 'Monthly Payment', 'responsive-mortgage-calculator' ),
		'quarterly_payment'     => __( 'Quarterly Payment', 'responsive-mortgage-calculator' ),
		'semiannual_payment'    => __( 'Semi-Annual Payment', 'responsive-mortgage-calculator' ),
		'yearly_payment'        => __( 'Yearly Payment', 'responsive-mortgage-calculator' ),
        
        // Detailed summary phrases
		'sy_text'   => sprintf( // Summary with number of years
			__( 'For a mortgage of %1$s amortized over %2$s years, your %3$s payment is', 'responsive-mortgage-calculator' ),
			$bs . '{total_amount}' . $be,
			$bs . '{amortization_years}' . $be,
			$bs . '{payment_period}' . $be
		),
		'sym1_text' => sprintf( // Summary with years and months
			__( 'For a mortgage of %1$s amortized over %2$s years and %3$s month, your %4$s payment is', 'responsive-mortgage-calculator' ),
			$bs . '{total_amount}' . $be,
			$bs . '{amortization_years}' . $be,
			$bs . '1' . $be,
			$bs . '{payment_period}' . $be
		),
		'sym_text'  => sprintf( // Summary with years and months
			__( 'For a mortgage of %1$s amortized over %2$s years and %3$s months, your %4$s payment is', 'responsive-mortgage-calculator' ),
			$bs . '{total_amount}' . $be,
			$bs . '{amortization_years}' . $be,
			$bs . '{amortization_months}' . $be,
			$bs . '{payment_period}' . $be
		),
		'syw1_text' => sprintf( // Summary with years and weeks
			__( 'For a mortgage of %1$s amortized over %2$s years and %3$s week, your %4$s payment is', 'responsive-mortgage-calculator' ),
			$bs . '{total_amount}' . $be,
			$bs . '{amortization_years}' . $be,
			$bs . '1' . $be,
			$bs . '{payment_period}' . $be
		),
		'syw_text'  => sprintf( // Summary with years and weeks
			__( 'For a mortgage of %1$s amortized over %2$s years and %3$s weeks, your %4$s payment is', 'responsive-mortgage-calculator' ),
			$bs . '{total_amount}' . $be,
			$bs . '{amortization_years}' . $be,
			$bs . '{amortization_weeks}' . $be,
			$bs . '{payment_period}' . $be
		),
		'mp_text'   => __( 'Mortgage Payment', 'responsive-mortgage-calculator' ),
		'tmwi_text' => __( 'Total Mortgage with Interest', 'responsive-mortgage-calculator' ),
		'twdp_text' => __( 'Total with Down Payment', 'responsive-mortgage-calculator' ),
	);
    
    return $localization;
}
