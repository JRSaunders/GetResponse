<?php


namespace GetResponse\ValueObject;


use GetResponse\CustomFieldsApi;
use GetResponse\GetResponse;

class CustomFieldByName extends CustomField {
	use ValidTrait;

	/**
	 * CustomFieldByName constructor.
	 *
	 * @param string $name
	 *
	 * @throws \GetResponse\Exception
	 */
	public function __construct( string $name ) {
		/**
		 * @var $customFieldsApi CustomFieldsApi
		 */
		$customFieldsApi = GetResponse::getInstance()->getFacetOrCreate( static::getApiClass() );

		$customFieldData = $customFieldsApi->getByName( $name );
		if ( $customFieldData && count( $customFieldData ) ) {
			$this->baseMapData( $customFieldData[0] );
			$this->valid = true;
		}

	}
}