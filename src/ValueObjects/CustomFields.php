<?php


namespace GetResponse\ValueObject;

use GetResponse\VerifyCustomFieldValue;

/**
 * Class CustomFields
 * @package GetResponse\ValueObject
 */
class CustomFields implements ToArray {
	/**
	 * @var [CustomField]
	 */
	protected $customFields = [];

	/**
	 * CustomFields constructor.
	 *
	 * @param array|null $customFields
	 *
	 * @throws \GetResponse\Exception
	 */
	public function __construct( ?array $customFields = null ) {
		$this->setCustomFields( $customFields );
	}

	/**
	 * @param VerifyCustomFieldValue $verifiedValue
	 *
	 * @return CustomFields
	 * @throws \GetResponse\Exception
	 */
	public function setByVerifyCustomFieldValue( VerifyCustomFieldValue $verifiedValue ): CustomFields {
		if ( $customFieldReady = $verifiedValue->getCustomFieldWithValueSet() ) {
			$this->addCustomField( $customFieldReady );
		}

		return $this;
	}

	/**
	 * @param array|null $customFields
	 *
	 * @return CustomFields
	 * @throws \GetResponse\Exception
	 */
	public function setCustomFields( ?array $customFields ): CustomFields {
		if ( ! $customFields ) {
			return $this;
		}
		foreach ( $customFields as &$customField ) {
			if ( ( ! $customField instanceof CustomField ) && is_object( $customField ) ) {
				$customField = new CustomField( $customField );
			}
			$this->addCustomField( $customField );
		}


		return $this;
	}

	/**
	 * @param CustomField $customField
	 *
	 * @return CustomFields
	 */
	public function addCustomField( CustomField $customField ): CustomFields {
		if ( ! $customField->getCustomFieldId() ) {
			return $this;
		}
		if ( $dupe = $this->getCustomFieldById( $customField->getCustomFieldId() ) ) {
			$this->unsetCustomFieldById( $customField->getCustomFieldId() );
		}
		$this->customFields[] = $customField;

		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray(): array {
		$returnArray                      = [];
		$returnArray['customFieldValues'] = [];

		if ( $customFields = $this->getCustomFields() ) {
			foreach ( $customFields as $customField ) {
				$returnArray['customFieldValues'][] = $customField->toArray();
			}
		}

		return $returnArray;
	}

	/**
	 * @return CustomField[]
	 */
	public function getCustomFields(): array {
		return $this->customFields;
	}

	/**
	 * @param string $name
	 *
	 * @return CustomField|null
	 */
	public function getCustomFieldByName( string $name ): ?CustomField {
		foreach ( $this->getCustomFields() as &$customField ) {
			if ( $customField->getName() == $name ) {
				return $customField;
			}
		}

		return null;
	}

	/**
	 * @param string $id
	 *
	 * @return CustomField|null
	 */
	public function getCustomFieldById( string $id ): ?CustomField {
		foreach ( $this->getCustomFields() as &$customField ) {
			if ( $customField->getCustomFieldId() == $id ) {
				return $customField;
			}
		}

		return null;
	}

	/**
	 * @param $id
	 *
	 * @return bool
	 */
	public function unsetCustomFieldById( $id ): bool {
		foreach ( $this->getCustomFields() as $key => $customField ) {
			if ( $customField->getCustomFieldId() == $id ) {
				unset( $this->customFields[ $key ] );

				return true;
			}
		}

		return false;
	}

	public function isSet(): bool {
		return (bool) $this->getCustomFields();
	}
}