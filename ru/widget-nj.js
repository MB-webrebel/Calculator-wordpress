/*
 * MLCalc.com
 *
 * Copyright (c) 2008-2021 ARSIDIAN LLC
 *
 */

function initializeMLCalcWidget(){
	var iframeHeight = 0;

	if(!jQuery('#MLCalcHolder, #MLCalcShader, #MLCalcClose').length) jQuery('body').prepend('<div id="MLCalcHolder"></div><div id="MLCalcShader"></div><div id="MLCalcClose" style="display:none">X</div>');

	jQuery('#MLCalcFormMortgageForm #dp INPUT').bind('keyup', function(){return calcDPValue()});
	jQuery('#MLCalcFormMortgageForm #ma INPUT').bind('keyup', function(){return calcDPValue()});
	jQuery('#MLCalcFormMortgageForm #dp INPUT').trigger('keyup');
	jQuery('#MLCalcFormMortgageForm').attr('autocomplete', 'off');
	jQuery('#MLCalcFormLoanForm').attr('autocomplete', 'off');

	jQuery('#MLCalcFormLoanForm').bind('submit', function(){
		if(typeof(mlcalc_amortization) == 'undefined') mlcalc_amortization = 'none';
		if(validateForm(jQuery(this)) == false) return false;
		iframeHeight = (mlcalc_amortization == 'none') ? 310 : 570;
		initFloatLayer(iframeHeight);
		jQuery(this).attr('target', 'MLCalcFrame');
	});
	jQuery('#MLCalcFormMortgageForm').bind('submit', function(){
		if(typeof(mlcalc_amortization) == 'undefined') mlcalc_amortization = 'none';
		if(validateForm(jQuery(this)) == false) return false;
		iframeHeight = (mlcalc_amortization == 'none') ? 327 : 578;
		initFloatLayer(iframeHeight);
		jQuery(this).attr('target', 'MLCalcFrame');
	});
	var now = new Date();
	jQuery('#MLCalcFormMortgageForm, #MLCalcFormLoanForm').each(function(i){
		jQuery('SELECT[name=sm] OPTION[value=' + (now.getMonth() + 1 > 12 ? 1 : now.getMonth() + 1) + '], SELECT[name=sy] OPTION[value=' + now.getFullYear() + ']', this).attr('selected', 'selected');
	});
};
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
	jQuery('#MLCalcHolder').find('#MLCalcFrame').css({width:frameWidth, height:iframeHeight}).on("load", function(){
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
function validateForm(form){
	var validations = {
		'ma':{
			'minval' : 1, 'maxval' : 999999999, 'required' : true,
			'errmsg' : "Цена недвижимости должна быть в пределах от 1 до 999,999,999"
		},
		'dp':{
			'minval' : 0, 'maxval' : 99, 'required' : false,
			'errmsg' : "Первоначальный взнос должен быть в пределах от 0 до 99"
		},
		'mt':{
			'minval' : 1, 'maxval' : 100, 'required' : true,
			'errmsg' : "Срок ипотеки должен быть от 1 до 100"
		},
		'pt':{
			'minval' : 0, 'maxval' : 999999, 'required' : false,
			'errmsg' : "Налог должен быть в пределах от 0 до 999,999"
		},
		'pi':{
			'minval' : 0, 'maxval' : 999999, 'required' : false,
			'errmsg' : "Страховка должна быть в пределах от 0 до 999,999"
		},
		'mi':{
			'minval' : 0, 'maxval' : 99.99, 'required' : false,
			'errmsg' : "PMI должен быть в пределах от 0 to 99.99"
		},
		'la':{
			'minval' : 1, 'maxval' : 999999999, 'required' : true,
			'errmsg' : "Размер кредита должен быть в пределах от 1 до 999,999,999"
		},
		'lt':{
			'minval' : 1, 'maxval' : 100, 'required' : true,
			'errmsg' : "Срок кредита должен быть в пределах от 1 до 100"
		},
		'ir':{
			'minval' : 0.01, 'maxval' : 99.99, 'required' : true,
			'errmsg' : "Процентная ставка должна быть в пределах от 0.01 до 99.99"
		}
	};
	var toSubmit = true;
	form.find('.valid').each(function(){
		var val  = parseFloat(jQuery(this).val().replace(/[^0-9\.]/g));
		var name = jQuery(this).attr('name');
		if((val == 'null' || isNaN(val)) && (validations[name]['required'] === false)) return true;
		if(val < validations[name]['minval'] || val > validations[name]['maxval'] || (val == 'null' || isNaN(val))){
			alert(validations[name]['errmsg']);
			jQuery(this).select();
			toSubmit = false;
			return false;
		};
	});
	return toSubmit;
};

function formatNum(num){
	return num.toString().replace(/(\d+)(\d{3})/, function(num, num1, num2){
		return ((num1.length < 3) ? num1 : formatNum(num1)) + "," + num2;
	});
};
function calcDPValue(){
	var ma = parseFloat(jQuery('#MLCalcFormMortgageForm #ma INPUT').val().replace(/[^0-9\.]/g, ''));
	var dp = parseFloat(jQuery('#MLCalcFormMortgageForm #dp INPUT').val());
	if(dp < 100) jQuery('#MLCalcFormMortgageForm #downPaymentValue').html('(' + mlcalc_currency_symbol + '' + formatNum(Math.round(ma * dp / 100)) + ')');
	if(dp >= 20){
		jQuery('#MLCalcFormMortgageForm #mi *').attr('disabled', 'disabled').addClass('disabled');
	} else {
		jQuery('#MLCalcFormMortgageForm #mi *').removeAttr('disabled').removeClass('disabled');
	};
};

mlcalcFrameIsShown = false;





if(typeof mlcalc_jquery_noconflict != "undefined") jQuery.noConflict();

if(typeof mlcalc_no_write == "undefined"){
	jQuery(document).ready(function() {
		initializeMLCalcWidget();
	});
} else {
	jQuery('#mlcalcWidgetHolder').prepend(FORM);
	initializeMLCalcWidget();
};


var _mlcalc_preload_img = new Image(312,44);
if(typeof(mlcalc_cdn_protocol) != 'undefined') _mlcalc_preload_img.src="https://www.mlcalc.com/themes/mlcalc/images/ajax-loader.gif";