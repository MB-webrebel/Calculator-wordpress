jQuery(document).ready(function() {
	var iframeHeight = 578;
	var mlcalcFrameIsShown = false;
	
	// Get the results divs.
	detailsDiv = jQuery('#lidd_mc_details');
	resultDiv = jQuery('#lidd_mc_results');
	summaryDiv = jQuery('#lidd_mc_summary');
	
	if(!jQuery('#MLCalcHolder, #MLCalcShader, #MLCalcClose').length){
    	jQuery('body').prepend('<div id="MLCalcHolder"></div><div id="MLCalcShader"></div><div id="MLCalcClose" style="display:none">X</div>');
    };
    
	jQuery('#lidd_mc_inspector').click(function() {
		event.preventDefault();
    	jQuery("#lidd_mc_mlc_form").submit();
	});
	
	jQuery("#lidd_mc_mlc_form").submit(function(){
		initFloatLayer(iframeHeight);
	});
	
	jQuery( ".lidd_mc_form" ).submit(function( event ) {
		
		// Prevent the form from being submitted.
		event.preventDefault();
		
		// Formatting function for outputting numbers with separator.
		function numberWithSeparator(x, separator, decimals, decimal_separator) {
            x = x.toFixed( decimals );
		    x = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, separator);
            if ( decimals > 0 && decimal_separator == ',' ) {
                x = x.substring( 0, x.lastIndexOf(".") ) + ',' + x.substring( x.lastIndexOf(".") + 1 );
            }
            return x;
		}
        // Indian formatting
        function indianSystem(x) {
            x = x.toFixed(0);
		    return x.slice(0, -3).toString().replace(/\B(?=(\d{2})+(?!\d))/g, ',') + ',' + x.slice(-3);
        }
        
        // Format numbers based on the given format
        function formatNumber(x) {
            switch (number_format) {
            case '1':
                return numberWithSeparator(x, ' ', 0, null);
                break;
            case '2':
                return numberWithSeparator(x, ' ', 2, '.');
                break;
            case '3':
                return numberWithSeparator(x, ' ', 3, '.');
                break;
            case '4':
                return numberWithSeparator(x, ',', 0, null);
                break;
            case '5':
                return indianSystem(x);
                break;
            case '6':
                return numberWithSeparator(x, ',', 2, '.');
                break;
            case '7':
                return numberWithSeparator(x, ',', 3, '.');
                break;
            case '8':
                return numberWithSeparator(x, '.', 0, null);
                break;
            case '9':
                return numberWithSeparator(x, '.', 2, ',');
                break;
            case '10':
                return numberWithSeparator(x, '.', 3, ',');
                break;
            case '11':
                return numberWithSeparator(x, '\'', 2, '.');
                break;
            default:
                return numberWithSeparator(x, ',', 2, '.');
                break;
            }
        }
		
		// Formatting function for currency codes
		function validateCurrencyCode(code) {
            if ( code !== null ) {
    			return code.replace(/[^A-Za-z]/, "");
            }
            return '';
		}
		
		// Function to format internationalized currencies
		function formatCurrency( amount ) {
			var formatted = currency_format;
			return formatted.replace( "{amount}", amount );
		}
		
		// Initialize variables.
		var error = false; // Marker. Assume there is an error.
		var period; // Store the payment period.
		var showSummary = false; // Record whether the summary is displayed or not.
		
		// Get variables.
		var ta = jQuery('#lidd_mc_total_amount').val();
		var dp = jQuery('#lidd_mc_down_payment').val();
		var ir = jQuery('#lidd_mc_interest_rate').val();
		var am = jQuery('#lidd_mc_amortization_period').val();
		var pp = jQuery('#lidd_mc_payment_period option:selected' ).val();
		var cp = lidd_mc_script_vars.compounding_period;
        var au = lidd_mc_script_vars.amortization_period_units;
        
        // Payment period may be hidden, so be careful when getting the value
        if ( typeof pp === 'undefined' ) {
            pp = jQuery( '#lidd_mc_payment_period' ).val();
        }
        
        // Currency format
		var currency = lidd_mc_script_vars.currency;
		var currency_code = validateCurrencyCode( lidd_mc_script_vars.currency_code );
		var currency_format = lidd_mc_script_vars.currency_format;
		var number_format = lidd_mc_script_vars.number_format;
        
        if ( currency_format.indexOf( '{amount}' ) == -1 ) {
            currency_format = '{currency}{amount}';
        }
		currency_format = currency_format.replace( '{currency}', currency );
		currency_format = currency_format.replace( '{code}', currency_code );
        
        // Setting for a minimum total amount
        var minimum_total_amount = +lidd_mc_script_vars.minimum_total_amount;
        
        // Setting for zero percent interest
        var allows_zero_percent_interest = lidd_mc_script_vars.zero_percent_interest;
        
		// Get the error reporting spans.
		var ta_error = jQuery('#lidd_mc_total_amount-error');
		var dp_error = jQuery('#lidd_mc_down_payment-error');
		var ir_error = jQuery('#lidd_mc_interest_rate-error');
		var am_error = jQuery('#lidd_mc_amortization_period-error');
		
        
        // Strip non-numeric characters from the total, down payment, interest rate, amortization period
        ta = ta.replace(/[^\d.]/g, '');
        dp = dp.replace(/[^\d.]/g, '');
        ir = ir.replace(/[^\d.]/g, '');
        am = am.replace(/[^\d.]/g, '');
        
        // Cast value as numbers
        ta = +ta;
        dp = +dp;
        ir = +ir;
        am = +am;
        pp = +pp;
        
		// Make sure the results divs are in their default states.
		detailsDiv.hide();
		resultDiv.html( '' );
		summaryDiv.html( '' );
		
		// ********************** //
		// ***** VALIDATION ***** //
		
		// A function to trigger an error.
		function triggerError( element, message ) {
			error = true;
			element.text( message );
			element.addClass( 'lidd_mc_error' );
		}
		
		// A function to remove an error.
		function removeError( element ) {
			element.text( '' );
			element.removeClass( 'lidd_mc_error' );
		}
		
		// Make sure total amount * 100 is an integer, or round it to one.
		if ( ta > minimum_total_amount ) {
			ta = Math.abs( Math.round( ta * 100 ) / 100 );
			removeError( ta_error );
		} else {
			triggerError( ta_error, lidd_mc_script_vars.ta_error );
		}
		// Down payment. If it is set, it must be less than the total amount.
		if ( ! dp || dp < ta ) {
			dp = Math.abs( Math.round( dp * 100 ) / 100 );
			removeError( dp_error );
		} else {
			triggerError( dp_error, lidd_mc_script_vars.dp_error );
		}
		// Interest rate. Positve value less than 100%. Leaves room for loan sharks.
		if ( ir < 100 && ( ir > 0 || ( ir >= 0 && allows_zero_percent_interest )) ) {
			removeError( ir_error );
		} else {
			triggerError( ir_error, lidd_mc_script_vars.ir_error );
		}
		// Validate the payment period, just in case.
		switch( pp ) {
			case 1:
				pp = 1;
				period = lidd_mc_script_vars.yearly;
				break;
			case 2:
				pp = 2;
				period = lidd_mc_script_vars.semiannual;
				break;
			case 4:
				pp = 4;
				period = lidd_mc_script_vars.quarterly;
				break;
			case 26:
				pp = 26;
				period = lidd_mc_script_vars.biweekly;
				break;
			case 52:
				pp = 52;
				period = lidd_mc_script_vars.weekly;
				break;
			default:
				pp = 12;
				period = lidd_mc_script_vars.monthly;
				break;
		}
        
        //console.log( 'payment period: ' + pp );
		// Amortization period.
		if ( am !== 0 ) {
			// The amortization period needs to fit nicely with the payment periods if there are decimals.
            if ( au == 1 ) am /= 12;
			am = Math.abs( Math.ceil( am * pp ) / pp );
			removeError( am_error );
		} else {
			triggerError( am_error, lidd_mc_script_vars.ap_error );
		}
		// Compounding period
		switch( cp ) {
			case '1':
				cp = 1;
				break;
			case '2':
				cp = 2;
				break;
			case '4':
				cp = 4;
				break;
			case '12':
			default:
				cp = 12;
				break;
		}
		
		// ***** END VALIDATION ***** //
		// ************************** //
		
		// If there are no errors, continue with the calculation.
		if ( error === false ) {
    		jQuery("#lidd_mc_mlc_form INPUT[name=ma]").val(ta);
    		jQuery("#lidd_mc_mlc_form INPUT[name=dp]").val(Number.parseInt(dp/ta*100));
    		jQuery("#lidd_mc_mlc_form INPUT[name=mt]").val(am);
    		jQuery("#lidd_mc_mlc_form INPUT[name=ir]").val(ir);
    		
    		if (lidd_mc_script_vars.popup == 0){
                jQuery("#lidd_mc_mlc_form").submit();
        		return false;
    		}
			
            var loan,
                np,
                nNom,
                rPeriod,
                rFactor,
                payment,
                result;
                
			// Calculate the total amount of the loan.
			loan = ta - dp;
		
			// Calculate the number of payments. This is amortization * payment period
			np = am * pp;
            
            if ( ! ir ) {
                payment = loan / np;
            }
            else {

    			// Convert the interest rate to a decimal. (Nominal Rate)
    			rNom = ir / 100;
			
    			// Calculate the rate per payment period
    			rPeriod = Math.pow( 1 + rNom / cp, cp / pp ) - 1;
			
    			// Calculate the total interest rate for the duration of the loan.
    			rFactor = Math.pow( rPeriod + 1, np );
		
    			// Calculate the payments.
    			payment = loan * ( ( rPeriod * rFactor ) / ( rFactor - 1 ) );
            }
		
			// Set the result for output.
			result = formatNumber( parseFloat( Math.round( payment * 100 ) / 100 ) );
			
			
			// Payment amount
			var display_result;
			switch ( pp ) {
                case 1:
					display_result = lidd_mc_script_vars.yearly_payment;
                    break;
                case 2:
					display_result = lidd_mc_script_vars.semiannual_payment;
                    break;
                case 4:
					display_result = lidd_mc_script_vars.quarterly_payment;
                    break;
				case 26:
					display_result = lidd_mc_script_vars.biweekly_payment;
					break;
				case 52:
					display_result = lidd_mc_script_vars.weekly_payment;
					break;
				case 12:
				default:
					display_result = lidd_mc_script_vars.monthly_payment;
					break;
			}
			display_result = display_result + ': ' + formatCurrency( result );
			
            // Print the result.
			resultDiv.html( '<p>' + display_result + '</p>' );
            
			// Summarize the data.
            if ( lidd_mc_script_vars.summary == 1 || lidd_mc_script_vars.summary == 2 ) {
                
                // Calculate the loan amount
    			var display_total = formatNumber( parseInt(ta - dp) );
                
                // Check for a remainder and select the summary text to display
    			if ( ( am - Math.floor(am) > 0 ) ) {
                    
                    // Calculate the remainder if amortization is more than one year
                    if ( am >= 1 ) {
        				var remainder = (np > pp) ? np % pp : pp % np;
                    }
                    // Less than one year
                    else {
                        var remainder = np;
                    }
                    
                    // Select the correct text based on the payment period
                    
                    // Yearly payments. No remainder expected but here just in case
                    if ( pp == 1 ) {
        				var summary = lidd_mc_script_vars.sy_text;
                    }
                    // Quarterly payments. Always plural if there is a remainder
                    else if ( pp == 4 ) {
						var summary = lidd_mc_script_vars.sym_text;
						summary = summary.replace( "{amortization_months}", remainder * 3 );
                    }
                    // Monthly payments
    				else if ( pp == 12 ) {
    					if ( remainder > 1 ) {
    						var summary = lidd_mc_script_vars.sym_text;
    						summary = summary.replace( "{amortization_months}", remainder );
    					} else {
    						var summary = lidd_mc_script_vars.sym1_text;
    					}
    				} else if ( pp == 52 ) {
    					if ( remainder > 1 ) {
    						var summary = lidd_mc_script_vars.syw_text;
    						summary = summary.replace( "{amortization_weeks}", remainder );
    					} else {
    						var summary = lidd_mc_script_vars.syw1_text;
    					}
    				} else {
    					var summary = lidd_mc_script_vars.syw_text;
    					summary = summary.replace( "{amortization_weeks}", remainder * 2 );
    				}
    			} else {
    				var summary = lidd_mc_script_vars.sy_text;
    			}
    			summary = summary.replace( "{total_amount}", formatCurrency( display_total ) );
    			summary = summary.replace( "{amortization_years}", Math.floor(am) );
    			summary = summary.replace( "{payment_period}", period );
    			summary = '<p>' + summary + ':</p>';
			
            
    			// Mortgage payment
    			summary += '<p>' + lidd_mc_script_vars.mp_text + ': <b class="lidd_mc_b">' + formatCurrency( result ) + '</b></p>';
			
    			// Total mortgage with interest
                if ( lidd_mc_script_vars.summary_interest == 1 ) {
        			summary += '<p>' + lidd_mc_script_vars.tmwi_text + ': <b class="lidd_mc_b">' + formatCurrency(
        				formatNumber(parseFloat( Math.round( (payment * np) * 100 ) / 100 ) )
        			) + '</b></p>';
                }
			
    			// Total with down payment
                if ( lidd_mc_script_vars.summary_downpayment == 1 ) {
        			if ( dp > 0 ) {
        				summary += '<p>' + lidd_mc_script_vars.twdp_text + ': <b class="lidd_mc_b">' + formatCurrency(
        					formatNumber(parseFloat( +dp + Math.round( (payment * np) * 100 ) / 100 ) )
        				) + '</b></p>';
        			}
                }
                
    			// Add the summary to the page
    			summaryDiv.html( summary );

			
/*
    			// Show the summary div when the result div is clicked.
    			if ( jQuery( '#lidd_mc_inspector' ).length ) {

    				jQuery('#lidd_mc_inspector').click(function() {
                        summaryDiv.slideToggle();
    				});
				
    			}
*/
                
            }

			// Show the details div for results and summary
			detailsDiv.show();
			
		}
		
	});
	
});
function initFloatLayer(iframeHeight){
	var viewportWidth  = jQuery(window).width();
	var viewportHeight = jQuery(window).height();

	var documentWidth  = 0;
	var documentHeight = 0;
	var viewportLeft   = 0;
	var viewportTop    = 0;

	if(document.body){
		documentWidth  = document.body.scrollWidth;
		documentHeight = document.body.scrollHeight;
		viewportLeft   = document.body.scrollLeft;
		viewportTop    = document.body.scrollTop;
	};
	if(document.documentElement){
		documentWidth  = Math.min(documentWidth, document.documentElement.scrollWidth);
		documentHeight = Math.max(documentHeight, document.documentElement.scrollHeight);
		viewportLeft   = Math.max(viewportLeft, document.documentElement.scrollLeft);
		viewportTop    = Math.max(viewportTop, document.documentElement.scrollTop);
	};

    var viewWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
    var frameWidth = Math.min(viewWidth, 740);

	var shaderWidth = Math.max(documentWidth, viewportWidth);
	var shaderHeight = Math.max(documentHeight, viewportHeight);
	jQuery('#MLCalcShader')
		.css({
			width:shaderWidth,
			height:shaderHeight,
			top:0,
			left:0,
			opacity:'0.5'
		})
		.show()
		.click(function(){
			mlcalcHideAll();
		});

	var holderLeft = parseInt((viewportWidth - frameWidth) / 2) + viewportLeft;
	var holderTop  = parseInt((viewportHeight - iframeHeight) / 2) + viewportTop;
	if(holderLeft < 0) holderLeft = 0;
	if(holderTop < 0) holderTop = 0;
	mlcalcFrameIsShown = true;
	jQuery('#MLCalcHolder')
		.css({
			width:frameWidth,
			height:iframeHeight,
			top:holderTop,
			left:holderLeft
		})
		.show();

	if(jQuery('#MLCalcHolder #MLCalcFrame').length < 1){
		jQuery('#MLCalcHolder').html('<iframe src="#" scrolling="no" id="MLCalcFrame" name="MLCalcFrame" width="0" height="0" frameborder="0" allowtransparency="true" style="background-color: transparent; display: none"></iframe><iframe id="garbageFrame" style="display:none"></iframe>')
	};
	jQuery(document).keyup(function(e) {
		if (e.keyCode == 27) mlcalcHideAll();
	});
	jQuery('#MLCalcHolder').find('#MLCalcFrame').css({width:frameWidth, height:iframeHeight}).on('load', function(){
		jQuery(this).show();
		jQuery('#MLCalcHolder #garbageFrame').attr('src', '');
		jQuery('#MLCalcClose').show().css({height:25, width:25}).css({top:holderTop, left:holderLeft+jQuery('#MLCalcHolder').width()-2-jQuery('#MLCalcClose').width()})
			.click(function(){
				mlcalcHideAll();
			})
			.hover(function(){
				jQuery(this).css({background:'#F5F5F5', color:'#808080'});
			}, function(){
				jQuery(this).css({background:'#D5D5D5', color:'#F5F5F5'});
			});
	});
};
function mlcalcHideAll(){
	if(!mlcalcFrameIsShown) return false;
	mlcalcFrameIsShown = false;
	jQuery('#MLCalcShader').fadeOut(300);
	jQuery('#MLCalcHolder, #MLCalcClose').hide();
	jQuery('#MLCalcFrame').remove();
};