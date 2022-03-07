<?php

namespace cBuilder\Helpers;

use cBuilder\Classes\CCBProTemplate;
use cBuilder\Classes\CCBTemplate;

/**
 * Cost Calculator Fields Helper
 */


class CCBFieldsHelper {

	/** Field templates */
	public static function get_fields_templates() {

		$templates = [
			'line'         => CCBTemplate::load('frontend/fields/cost-line'),
			'html'         => CCBTemplate::load('frontend/fields/cost-html'),
			'toggle'       => CCBTemplate::load('frontend/fields/cost-toggle'),
			'text-area'    => CCBTemplate::load('frontend/fields/cost-text'),
			'checkbox'     => CCBTemplate::load('frontend/fields/cost-checkbox'),
			'quantity'     => CCBTemplate::load('frontend/fields/cost-quantity'),
			'radio-button' => CCBTemplate::load('frontend/fields/cost-radio'),
			'range-button' => CCBTemplate::load('frontend/fields/cost-range'),
			'drop-down'    => CCBTemplate::load('frontend/fields/cost-drop-down'),
		];

		if ( ccb_pro_active() ) {
			$templates['date-picker'] = CCBProTemplate::load('frontend/fields/cost-date-picker');
			$templates['multi-range'] = CCBProTemplate::load('frontend/fields/cost-multi-range');
		}

		return $templates;
	}

	/** Get all posible fields */
	public static function fields() {

		return [
			['name'  => __('Checkbox', 'cost-calculator-builder'),
			 'alias' => 'checkbox',
			 'type'  => 'checkbox',
			 'icon'  => 'fas fa-check-square',
			 'description' => 'checkbox fields'],
			['name' => __('Radio', 'cost-calculator-builder'),
			 'alias' => 'radio',
			 'type' => 'radio-button',
			 'icon' => 'fas fa-dot-circle',
			 'description' => 'radio fields'],
			['name' => __('Date Picker', 'cost-calculator-builder'),
			 'alias' => 'datepicker',
			 'type' => 'date-picker',
			 'icon' => 'fas fa-calendar-alt',
			 'description' => 'date picker fields'],
			['name' => __('Range Button', 'cost-calculator-builder'),
			 'alias' => 'range',
			 'type' => 'range-button',
			 'icon' => 'fas fa-exchange-alt',
			'description' => 'range slider'],
			['name' => __('Drop Down', 'cost-calculator-builder'),
			 'alias' => 'drop-down',
			 'type' => 'drop-down',
			'icon' => 'fas fa-chevron-down',
			'description' => 'drop-down fields'],
			['name' => __('Text', 'cost-calculator-builder'),
			 'alias' => 'text-area',
			 'type' => 'text-area',
			 'icon' => 'fas fa-font',
			 'description' => 'text fields'],
			['name' => __('Html', 'cost-calculator-builder'),
			 'alias' => 'html',
			 'type' => 'html',
			'icon' => 'fas fa-code',
			'description' => 'html elements'],
			['name' => __('Total', 'cost-calculator-builder'),
			 'alias' => 'total',
			 'type' => 'total',
			'icon' => 'fas fa-calculator',
			'description' => 'total fields'],
			['name' => __('Line', 'cost-calculator-builder'),
			 'alias' => 'line',
			 'type' => 'line',
			'icon' => 'fas fa-ruler-horizontal',
			'description' => 'horizontal ruler'],
			['name' => __('Quantity', 'cost-calculator-builder'),
			 'alias' => 'quantity',
			 'type' => 'quantity',
			 'icon' => 'fas fa-hand-peace',
			 'description' => 'quantity fields'],
			['name' => __('Multi Range', 'cost-calculator-builder'),
			 'alias' => 'multi-range',
			'type' => 'multi-range',
			'icon' => 'fas fa-exchange-alt',
			'description' => 'multi-range field'],
			['name' => __('Toggle Button', 'cost-calculator-builder'),
			'alias' => 'toggle',
			'type' => 'toggle',
			'icon' => 'fas fa-toggle-on',
			'description' => 'toggle fields'
			],
		];
	}
}