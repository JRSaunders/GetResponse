<?php


namespace GetResponse;


use GetResponse\ValueObject\CustomField;

/**
 * Class VerifyCustomFieldValue
 * @package GetResponse
 */
class VerifyCustomFieldValue {
	protected $customField;
	protected $value;
	protected $verified = false;
	protected $safeValue;

	/**
	 * VerifyCustomFieldValue constructor.
	 *
	 * @param CustomField $customField
	 * @param $value
	 */
	public function __construct( CustomField $customField, $value ) {
		$this->customField = $customField;
		$this->value       = $value;
		$this->safeValue   = $value;
	}

	/**
	 * @return mixed
	 */
	public function getSafeValue() {
		return $this->safeValue;
	}


	/**
	 * @return bool
	 */
	public function isVerified(): bool {
		return $this->verified;
	}

	/**
	 * @param bool $verified
	 *
	 * @return VerifyCustomFieldValue
	 */
	protected function setVerified( bool $verified ): VerifyCustomFieldValue {
		$this->verified = $verified;

		return $this;
	}

	/**
	 * @return bool
	 */
	protected function testValueType(): bool {
		$valueType = $this->customField->getValueType();
		switch ( $valueType ) {
			case 'string':
			case 'text':
			case 'textarea':
				if ( ! is_array( $this->value ) ) {
					return ( ( is_string( $this->value ) || is_int( $this->value ) ) && strlen( $this->value ) );
				} else {
					$needToPass = count( $this->value );
					$passed     = 0;
					if ( $needToPass ) {
						foreach ( $this->value as $value ) {
							if ( ( is_string( $value ) || is_int( $value ) ) && strlen( $value ) ) {
								$passed ++;
							}
						}
					}

					return ( $passed == $needToPass );

				}
				break;
			case 'number':
				if ( ! is_array( $this->value ) ) {
					return is_numeric( $this->value );
				} else {
					$needToPass = count( $this->value );
					$passed     = 0;
					if ( $needToPass ) {
						foreach ( $this->value as $value ) {
							if ( is_numeric( $value ) ) {
								$passed ++;
							}
						}
					}

					return ( $passed == $needToPass );
				}
				break;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	protected function testFieldType(): bool {
		$fieldType = $this->customField->getFieldType();
		switch ( $fieldType ) {
			case 'string':
			case 'text':
			case 'textarea':
				return ( ( is_string( $this->value ) || is_int( $this->value ) ) && strlen( $this->value ) );
				break;
			case 'single_select':
			case 'radio':
			case 'select':
			case 'multi_select':

				if ( is_array( $this->value ) ) {
					$result = $this->testFixMultiSelect();
					if ( is_bool( $result ) ) {
						return $result;
					}
				} else {
					$result = $this->testFixSingleSelect();
					if ( is_bool( $result ) ) {
						return $result;
					}
				}
				break;
		}

		return false;

	}

	/**
	 * @return bool|null
	 */
	private function testFixSingleSelect(): ?bool {
		$values = $this->customField->getValues();
		if ( $values ) {
			foreach ( $values as $value ) {
				if ( $value == $this->value ) {
					$this->safeValue = $value;

					return true;
				}
				if ( strtolower( $value ) == strtolower( $this->value ) ) {
					$this->safeValue = $value;

					return true;
				}
			}
		}

		return null;
	}

	/**
	 * @return bool|null
	 */
	private function testFixMultiSelect(): ?bool {

		$values = $this->customField->getValues();
		if ( $values && is_array( $this->value ) ) {
			$this->safeValue = [];
			foreach ( $this->value as $propValue ) {
				foreach ( $values as $value ) {
					if ( $value == $propValue ) {
						$this->safeValue[] = $value;
						continue;
					}
					if ( strtolower( $value ) == strtolower( $propValue ) ) {
						$this->safeValue[] = $value;

						continue;
					}
				}
			}
		}

		if ( ! $this->safeValue && is_array( $this->safeValue ) ) {
			return true;
		}

		if ( $this->safeValue && is_array( $this->safeValue ) && count( $this->safeValue ) ) {
			return true;
		}

		return null;
	}


	/**
	 * @return bool
	 */
	public function verify(): bool {
		if ( $this->testFieldType() && $this->testValueType() ) {
			$this->setVerified( true );

			return true;
		}

		return false;
	}

	/**
	 * @return CustomField|null
	 * @throws Exception
	 */
	public function getCustomFieldWithValueSet(): ?CustomField {

		if ( $this->verify() ) {
			$customField = ( new CustomField() )->setCustomFieldId( $this->customField->getCustomFieldId() );
			if ( is_array( $this->getSafeValue() ) ) {
				$customField->setValues( $this->getSafeValue() );
			} else {
				$customField->setValue( $this->getSafeValue() );
			}

			return $customField;
		}

		return null;
	}

}