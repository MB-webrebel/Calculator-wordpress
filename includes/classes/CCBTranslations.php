<?php

namespace cBuilder\Classes;

class CCBTranslations {

	/**
	 * Frontend Translation Data
	 * @return array
	 */
	public static function get_frontend_translations() {

		$translations = [
			'empty_end_date_error' => esc_html__('Please select the second date', 'cost-calculator-builder'),
			'required_field'       => esc_html__('This field is required', 'cost-calculator-builder'),
			'select_date_range'    => esc_html__('Select Date Range', 'cost-calculator-builder'),
			'select_date'          => esc_html__('Select Date', 'cost-calculator-builder'),
			'high_end_date_error'  => esc_html__('To date must be greater than from date', 'cost-calculator-builder'),
			'high_end_multi_range' => esc_html__('To value must be greater than from value', 'cost-calculator-builder'),
		];

		return $translations;
	}

	public static function get_backend_translations() {
		$translations = [
			'bulk_action_attention' => esc_html__('Are you sure to "%s" choosen Calculators?', 'cost-calculator-builder'),
			'copied' => esc_html__('Copied', 'cost-calculator-builder'),
			'not_selected_calculators' => esc_html__('No calculators were selected', 'cost-calculator-builder'),
			'select_bulk' => esc_html__('Select bulk action', 'cost-calculator-builder'),
			'changes_saved' => esc_html__('Changes Saved', 'cost-calculator-builder'),
			'calculator_deleted' => esc_html__('Calculator Deleted', 'cost-calculator-builder'),
			'calculator_duplicated' => esc_html__('Calculator Duplicated', 'cost-calculator-builder'),
			'condition_link_saved' => esc_html__('Condition Link Saved', 'cost-calculator-builder'),
		];

		return $translations;
	}
}