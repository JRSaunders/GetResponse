<?php


namespace GetResponse\ValueObject;


use GetResponse\CustomFieldsApi;
use GetResponse\GetResponse;

/**
 * Class CustomField
 * @package GetResponse\ValueObject
 */
class CustomField extends Base {
	/**
	 * @var string
	 */
	protected $customFieldId;
	/**
	 * @var array
	 */
	protected $values = [];
	/**
	 * @var string
	 */
	protected $fieldType;
	/**
	 * @var string
	 */
	protected $format;
	/**
	 * @var string
	 */
	protected $valueType;
	/**
	 * @var bool
	 */
	protected $hidden = false;
	/**
	 * @var
	 */
	protected $type;

	const MULTI_SELECT = 'multi_select';

	const SINGLE_SELECT = 'single_select';

	public function toArray(): array {
		return [ "customFieldId" => $this->getCustomFieldId(), "values" => $this->values ];
	}

	/**
	 * @return string
	 */
	public function getCustomFieldId(): string {
		return $this->customFieldId;
	}

	/**
	 * @param string $customFieldId
	 *
	 * @return CustomField
	 */
	public function setCustomFieldId( string $customFieldId ): CustomField {
		$this->customFieldId = $customFieldId;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getValues(): ?array {
		return $this->values;
	}

	/**
	 * @param array $values
	 *
	 * @return CustomField
	 */
	public function setValues( array $values ): CustomField {
		$this->values = $values;

		return $this;
	}

	/**
	 * @param string $value
	 *
	 * @return CustomField
	 */
	public function setValue( string $value ): CustomField {
		$this->setValues( [ trim( $value ) ] );

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFieldType(): ?string {
		return $this->fieldType;
	}

	/**
	 * @param string $fieldType
	 *
	 * @return CustomField
	 */
	public function setFieldType( string $fieldType ): CustomField {
		$this->fieldType = $fieldType;

		return $this;
	}

	/**
	 * @param string $format
	 *
	 * @return CustomField
	 */
	public function setFormat( string $format ): CustomField {
		$this->format = $format;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFormat(): ?string {
		return $this->format;
	}

	/**
	 * @param string $valueType
	 *
	 * @return CustomField
	 */
	public function setValueType( string $valueType ): CustomField {
		$this->valueType = $valueType;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getValueType(): ?string {
		return $this->valueType;
	}

	/**
	 * @param string $type
	 *
	 * @return CustomField
	 */
	public function setType( string $type ): CustomField {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getType(): ?string {
		return $this->type;
	}

	/**
	 * @param bool $hidden
	 *
	 * @return CustomField
	 */
	public function setHidden( bool $hidden ): CustomField {
		$this->hidden = $hidden;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isHidden(): bool {
		return $this->hidden;
	}

	protected function mapData( \stdClass $data ): void {

		/**
		 *     ["customFieldId"]=>
		 * string(1) "n"
		 * ["href"]=>
		 * string(50) "https://api3.getresponse360.com/v3/custom-fields/n"
		 * ["name"]=>
		 * string(6) "status"
		 * ["fieldType"]=>
		 * string(8) "textarea"
		 * ["format"]=>
		 * string(8) "textarea"
		 * ["valueType"]=>
		 * string(6) "string"
		 * ["type"]=>
		 * string(8) "textarea"
		 * ["hidden"]=>
		 * string(4) "true"
		 * ["values"]=>
		 * array(0) {
		 * }
		 */

	}

	protected function getApiClass(): string {
		return CustomFieldsApi::class;
	}
}