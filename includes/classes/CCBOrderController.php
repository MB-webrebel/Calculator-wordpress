<?php

namespace cBuilder\Classes;

use cBuilder\Classes\Database\Orders;

class CCBOrderController {

	public static function create() {
		check_ajax_referer('ccb_add_order', 'nonce');

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$req = json_decode(stripslashes($_POST['data']));

			$data = [
				'calc_id'           => !empty($req->id) ? sanitize_text_field($req->id) : null,
				'calc_title'        => !empty($req->calcName) ? sanitize_text_field($req->calcName) : null,
				'total'             => !empty($req->total) ? sanitize_text_field($req->total) : 0.00,
				'status'            => !empty($req->status) ? sanitize_text_field($req->status) : 'pending',
				'currency'          => !empty($req->currency) ? sanitize_text_field($req->currency) : null,
				'payment_method'    => !empty($req->paymentMethod) ? sanitize_text_field($req->paymentMethod) : 'no_payments',
				'order_details'     => json_encode($req->orderDetails),
				'form_details'      => json_encode($req->formDetails),
				'created_at'        => wp_date('Y-m-d H:i:s'),
				'updated_at'        => wp_date('Y-m-d H:i:s'),
			];

			if (is_float($data['total'])) {
				$data['total'] = number_format($data['total'], 2);
			}

			$id = Orders::create_order($data);

			wp_send_json_success([ 'status' => 'success', 'order_id' => $id ]);
		}
	}

	public static function update() {
		check_ajax_referer('ccb_update_order', 'nonce');


		if ( !empty( $_POST['ids'] ) ) {
			$ids = !empty($_POST['ids']) ? sanitize_text_field($_POST['ids']) : null;
			$status = !empty($_POST['status']) ? sanitize_text_field($_POST['status']) : null;

			$ids = explode(',', $ids);
			$d = implode(',', array_fill(0, count($ids), '%d'));
			$args = $ids;
			array_unshift($args, $status);

			try {
				Orders::update_orders($d, $args);
				wp_send_json([
					'status' => 200,
					'message' => 'Success'
				]);
				throw new Exception('Error');
			} catch (Exception $e) {
				header("Status: 500 Server Error");
			}
		}
	}

	public static function delete() {
		check_ajax_referer('ccb_delete_order', 'nonce');

		if ( !current_user_can( 'manage_options' ) ) {
			return false;
		}

		$ids = !empty($_POST['ids']) ? sanitize_text_field($_POST['ids']) : null;
		$ids = explode(',', $ids);
		$d = implode(',', array_fill(0, count($ids), '%d'));


		try {
			Orders::delete_orders($d, $ids);
			wp_send_json([
				'status' => 200,
				'message' => 'success'
			]);
			throw new Exception("Error");
		} catch (Exception $e) {
			header("Status: 500 Server Error");
		}
	}

	public static function completeOrderById($id) {
		$id = sanitize_text_field($id);

		try {
			Orders::complete_order_by_id($id);
			wp_send_json([
				'status' => 200,
				'message' => 'Success'
			]);
			throw new Exception('Error');
		} catch (Exception $e) {
			header("Status: 500 Server Error");
		}
	}

	public static function orders() {
		check_ajax_referer('ccb_orders', 'nonce');

		$calc_list = CCBCalculators::get_calculator_list();
		$calc_id_list = array_map(function ($item) {
			return $item['id'];
		}, $calc_list);

		$calculators = Orders::existing_calcs();

		if (empty($calculators)) {
			wp_send_json([
				'data' => [],
				'total_count' => 0,
				'calc_list' => $calculators
			]);
			exit();
		}

		$default_payment_types = ["paypal","no_payments","stripe"];
		$default_payment_status = ["pending", "complete"];
		$default_calc_ids = array_map(function ($cal) { return $cal['calc_id']; }, $calculators);

		if (!empty($_GET['status'])) {
			$default_payment_status = $_GET['status'] === 'all' ? $default_payment_status : array(sanitize_text_field($_GET['status']));
		}

		if (!empty($_GET['calc_id'])) {
			$default_calc_ids = $_GET['calc_id'] === 'all' ? $default_calc_ids : array($_GET['calc_id']);
		}

		if (!empty($_GET['payment'])) {
			$default_payment_types = $_GET['payment'] === 'all' ? $default_payment_types : array(sanitize_text_field($_GET['payment']));
		}

		$payment_method = implode(',', array_fill(0, count($default_payment_types), '%s'));
		$payment_status = implode(',', array_fill(0, count($default_payment_status), '%s'));
		$calc_ids = implode(',', array_fill(0, count($default_calc_ids), '%d'));

		$page = !empty($_GET['page']) ? (int) sanitize_text_field($_GET['page']) : 1;
		$limit = !empty($_GET['limit']) ? sanitize_text_field($_GET['limit']) : 5;
		$orderBy = !empty($_GET['sortBy']) ? sanitize_sql_orderby($_GET['sortBy']) : sanitize_sql_orderby('total');
		$sorting = !empty($_GET['direction']) ? sanitize_sql_orderby(strtoupper($_GET['direction'])) : sanitize_sql_orderby('ASC');
		$offset = $page === 1 ? 0 : ($page - 1) * $limit;
		$orders_args = array_merge($default_payment_types, $default_payment_status, $default_calc_ids);

		$total = Orders::total_orders([
			'payment_method' => $payment_method,
			'payment_status' => $payment_status,
			'calc_ids'       => $calc_ids,
			'args'           => $orders_args,
		]);

		array_push($orders_args, (int) $limit, (int) $offset);

		try {
			$orders = Orders::get_all_order([
				'payment_method' => $payment_method,
				'payment_status' => $payment_status,
				'calc_ids'       => $calc_ids,
				'orderBy'        => $orderBy,
				'sorting'        => $sorting,
				'args'           => $orders_args,
			]);
			$result = [];

			foreach ($orders as $order) {
				$form_details = json_decode($order['form_details'])->fields;

				if (!in_array($order['calc_id'], $calc_id_list)) {
					$order['calc_deleted'] = true;
				}

				foreach ($form_details as $detail) {
					if ($detail->name === 'email' || $detail->name === 'your-email') {
						$order['user_email'] = $detail->value;
					}
				}

				$order['order_details'] = json_decode($order['order_details']);
				$order['form_details'] = json_decode($order['form_details']);
				array_push($result, $order);
			}


			wp_send_json([
				'data' => $result,
				'total_count' => $total,
				'calc_list' => $calculators
			]);

			throw new Exception('Error');
		} catch (Exception $e) {
			header("Status: 500 Server Error");
		}
	}
}