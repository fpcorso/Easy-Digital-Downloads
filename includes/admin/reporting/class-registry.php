<?php
/**
 * Reports API - Reports Base Registry
 *
 * @package     EDD
 * @subpackage  Admin/Reports
 * @copyright   Copyright (c) 2018, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */
namespace EDD\Admin\Reports;

use EDD\Utils;
use EDD\Admin\Reports\Exceptions as Reports_Exceptions;

/**
 * Defines the construct for building a reports item registry.
 *
 * @since 3.0.0
 */
class Registry extends Utils\Registry {

	/**
	 * Reports item error label.
	 *
	 * Used for customizing exception messages to the current registry instance. Default 'reports item'.
	 *
	 * @since 3.0
	 * @var   string
	 */
	public static $item_error_label = 'reports item';

	/**
	 * Validates a list of report item attributes.
	 *
	 * @since 3.0
	 *
	 * @throws \EDD_Exception if an attribute was empty.
	 *
	 * @param array  $attributes List of attributes to check for emptiness.
	 * @param string $item_id    Item ID.
	 * @param array  $skip       Optional. List of attributes to skip validating.
	 * @return void
	 */
	public function validate_attributes( $attributes, $item_id, $skip = array() ) {
		foreach ( $attributes as $attribute => $value ) {
			if ( in_array( $attribute, $skip, true ) ) {
				continue;
			}

			if ( empty( $value ) ) {
				throw Reports_Exceptions\Invalid_Parameter::from( $attribute, __METHOD__, $item_id );
			}
		}
	}

	/**
	 * Retrieves all registered items with a given sorting scheme.
	 *
	 * @since 3.0
	 *
	 * @param string $sort Optional. How to sort the list of registered items before retrieval.
	 *                     Accepts 'priority' or 'ID' (alphabetized by item ID), or empty (none).
	 *                     Default empty.
	 */
	public function get_items_sorted( $sort = '' ) {
		// If sorting, handle it before retrieval from the ArrayObject.
		switch( $sort ) {
			case 'ID':
				parent::ksort();
				break;

			case 'priority':
				parent::uasort( function( $a, $b ) {
					if ( $a['priority'] == $b['priority'] ) {
						return 0;
					}

					return ( $a['priority'] < $b['priority'] ) ? -1 : 1;
				} );
				break;

			default: break;
		}

		return parent::get_items();
	}
}
