<?php

namespace cBuilder\Classes\Database;

use cBuilder\Classes\CCBCalculators;
use cBuilder\Classes\Vendor\DataBaseModel;
use PHPMailer\PHPMailer\Exception;

class Orders extends DataBaseModel {
	public static $pending  = 'pending';
	public static $paid     = 'paid';

	public static $statusList = ['pending', 'paid'];

	/**
	 * Create Table
	 */
	public static function create_table() {
		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$table_name = self::_table();
		$primary_key = self::$primary_key;

		$sql = "CREATE TABLE {$table_name} (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			calc_id  INT UNSIGNED NOT NULL,
			calc_title VARCHAR(255) DEFAULT NULL,
			total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
			status VARCHAR(20) NOT NULL DEFAULT 'pending',
			currency CHAR(20) NOT NULL,
			payment_method VARCHAR(30) NOT NULL DEFAULT 'no_payments',
			order_details longtext DEFAULT NULL,
			form_details longtext DEFAULT NULL,
			created_at TIMESTAMP NOT NULL,
			updated_at TIMESTAMP NOT NULL,
			PRIMARY KEY ({$primary_key}),
		    INDEX `idx_calc_id` (`calc_id`),
		    INDEX `idx_created_at` (`created_at`),
		    INDEX `idx_status` (`status`),
		    INDEX `idx_total` (`total`)
		) {$wpdb->get_charset_collate()};";

		maybe_create_table($table_name, $sql);
	}

	/**
	 * Create Order
	 */
	public static function create_order($data) {

		self::insert($data);

		return self::insert_id();
	}

	/**
	 * Update order
	 */
	public static function update_orders($d, $args) {
		global $wpdb;
		$table_name = self::_table();
		$sql = $wpdb->prepare("UPDATE $table_name SET status = %s WHERE id IN ($d)", $args);
		$wpdb->get_results($sql);
	}

	/**
	 * Delete Order
	 */
	public static function delete_orders($d, $ids) {
		global $wpdb;
		$table_name = self::_table();
		$sql = $wpdb->prepare("DELETE FROM $table_name WHERE id IN ($d)", $ids);
		$wpdb->get_results($sql);
	}

	/**
	 * Complete Order by id
	 */
	public static function complete_order_by_id($id) {
		global $wpdb;
		$table_name = self::_table();
		return $wpdb->get_results("UPDATE $table_name SET status = 'complete' WHERE id = $id");
	}

	public static function existing_calcs() {
		global $wpdb;
		$table_name = self::_table();
		return $wpdb->get_results("SELECT DISTINCT calc_id, calc_title FROM $table_name", ARRAY_A);
	}

	public static function total_orders($params) {
		global $wpdb;
		$table_name = self::_table();
		$payment_method = $params['payment_method'];
		$payment_status = $params['payment_status'];
		$calc_ids       = $params['calc_ids'];
		$args           = $params['args'];

		$total_sql = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE payment_method in ($payment_method) AND status in ($payment_status) AND calc_id in ($calc_ids)", $args);

		return $wpdb->get_results($total_sql, ARRAY_A)[0]["COUNT(*)"];
	}

	/**
	 *  Get all orders
	 */
	public static function get_all_order($params) {
		global $wpdb;
		$table_name = self::_table();
		$payment_method = $params['payment_method'];
		$payment_status = $params['payment_status'];
		$calc_ids       = $params['calc_ids'];
		$sorting        = $params['sorting'];
		$orderBy        = $params['orderBy'];
		$args           = $params['args'];

		$orders_sql = $wpdb->prepare("SELECT * FROM $table_name WHERE payment_method in ($payment_method) AND status in ($payment_status) AND  calc_id in ($calc_ids) ORDER BY $orderBy $sorting LIMIT %d OFFSET %d", $args);
		return $wpdb->get_results($orders_sql, ARRAY_A);
	}
}

