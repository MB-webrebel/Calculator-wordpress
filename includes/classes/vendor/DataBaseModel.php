<?php

namespace cBuilder\Classes\Vendor;

	class DataBaseModel {
		public static $primary_key = 'id';
		protected static $table_prefix = 'cc_';

		/**
		 * Generate Table Name from Called Class
		 * @return string
		 */
		public static function _table() {
			global $wpdb;
			$classname = explode('\\', strtolower( get_called_class() ));
			$tablename = self::$table_prefix . end( $classname );
			return $wpdb->prefix . $tablename;
		}

		/**
		 * Insert data to Table
		 * @param $data
		 */
		public static function insert( $data ) {
			global $wpdb;

			add_filter( 'query', [self::class, 'wp_db_null_value'] );

			if ( isset($data['nonce']) ) unset( $data['nonce'] );

			$data = array_map(function($item) {
				if(trim($item, ' \'"')){
					return trim($item);
				}
				return null;
			}, $data);

			$wpdb->insert( self::_table(), $data );

			remove_filter( 'query', [self::class, 'wp_db_null_value'] );
		}

		/**
		 * Update data in Table with $where clouse
		 * @param $data
		 * @param $where
		 */
		public static function update( $data, $where ) {
			global $wpdb;

			add_filter( 'query', [self::class, 'wp_db_null_value'] );

			if ( isset($data['nonce']) ) unset( $data['nonce'] );

			$data = array_map(function($item) {
				if(trim($item, ' \'"')){
					return trim($item);
				}
				return null;
			}, $data);

			$wpdb->update( self::_table(), $data, $where );

			remove_filter( 'query', [self::class, 'wp_db_null_value'] );
		}

		/**
		 * Delete data from Table by ID
		 * @param $id
		 * @return mixed
		 */
		public static function delete( $value ) {
			global $wpdb;
			$sql = sprintf( 'DELETE FROM %s WHERE %s = %%s', self::_table(), static::$primary_key );

			return $wpdb->query( $wpdb->prepare( $sql, $value ) );
		}

		/**
		 * Get inserted data ID
		 * @return int
		 */
		public static function insert_id() {
			global $wpdb;
			return $wpdb->insert_id;
		}

		/**
		 * Replace the 'NULL' string with NULL
		 * @param string $query
		 * @return string $query
		 */
		public static function wp_db_null_value( $query ) {
			return str_ireplace( "'NULL'", "NULL", $query );
		}

	}
?>