<?php


namespace GetResponse;


use GetResponse\ValueObject\Contact;
use GetResponse\ValueObject\CustomFields;

/**
 * Class ContactsApi
 * @package GetResponse
 */
class ContactsApi extends GetResponseApi {

	protected $campaignId;

	/**
	 * @return string | null
	 */
	public function getCampaignId(): ?string {
		return $this->campaignId;
	}

	/**
	 * @param string|null $campaignId
	 *
	 * @return ContactsApi
	 */
	public function setCampaignId( ?string $campaignId ): ContactsApi {
		$this->campaignId = $campaignId;

		return $this;
	}


	/**
	 * @param string $contactId
	 * @param CustomFields $customFields
	 * @param \Closure|null $upsertFailedCallback
	 *
	 * @return bool|null
	 */
	public function upsertCustomFieldsById( string $contactId, CustomFields $customFields, ?\Closure $upsertFailedCallback = null ): ?bool {
		try {
			$returnValues = $this->getCurlRequest( $contactId . '/custom-fields', $customFields->toArray() )->post()->jsonEncodeSendData()->getDecodedJsonResponse();
			if ( $returnValues && isset( $returnValues->customFieldValues ) ) {
				$returnedCustomFields = new CustomFields( $returnValues->customFieldValues );
				$updated              = 0;
				$customFieldArray     = $customFields->getCustomFields();
				$toUpdate             = count( $customFieldArray );
				$mismatch             = '(';
				if ( $customFieldArray ) {
					foreach ( $customFieldArray as $customField ) {
						$id     = $customField->getCustomFieldId();
						$values = $customField->getValues();
						if ( $returnCustomField = $returnedCustomFields->getCustomFieldById( $id ) ) {
							if ( $returnCustomField->getValues() === $values ) {
								$updated ++;
							} else {
								$returnValues = $returnCustomField->getValues();

								foreach ( $returnValues as &$val ) {
									if ( is_string( $val ) ) {
										$val = trim( strtolower( $val ) );
									}
								}
								$sentValues = $values;

								foreach ( $sentValues as &$val ) {
									if ( is_string( $val ) ) {
										$val = trim( strtolower( $val ) );
									}
								}
								if ( ! array_diff( $sentValues, $returnValues ) ) {
									$updated ++;
								} else {
									$mismatch .= print_r( $returnCustomField->getValues(), true ) . ' != ' . print_r( $values, true ) . ' ,';
								}
							}
						}
					}
				}
				$mismatch .= ')';
				if ( $updated == $toUpdate ) {
					return true;
				}
				throw new Exception( "Upsert Failed updates required {$toUpdate} -> updates done {$updated} " . $mismatch );
			} else {
				return false;
			}
		} catch ( Exception $exception ) {
			$this->setException( $exception );
			if ( $upsertFailedCallback ) {
				return (bool) call_user_func_array( $upsertFailedCallback, [ $exception, $this ] );
			}
		}

		return null;
	}

	/**
	 * @param Contact $contact
	 * @param \Closure|null $upsertFailedCallback
	 *
	 * @return bool|null
	 * @throws Exception
	 */
	public function upsertCustomFieldsByContact( Contact $contact, ?\Closure $upsertFailedCallback = null ): ?bool {
		return $this->upsertCustomFieldsById( $contact->getContactId(), $contact->getCustomFields(), $upsertFailedCallback );
	}

	/**
	 * @param Contact $contact
	 * @param \Closure|null $upsertFailedCallback
	 *
	 * @return bool|null
	 */
	public function updateByContact( Contact $contact, ?\Closure $upsertFailedCallback = null ): ?bool {
		return $this->updateById( $contact->getContactId(), $contact->toArray(), $upsertFailedCallback );
	}

	/**
	 * @param Contact $contact
	 * @param \Closure|null $createFailedCallback
	 *
	 * @return bool|null
	 */
	public function createByContact( Contact $contact, ?\Closure $createFailedCallback = null ): ?bool {
		return $this->create( $contact->toArray(), $createFailedCallback );
	}


	/**
	 * @param string $email
	 *
	 * @return \stdClass[]|null
	 */
	public function getByEmail( string $email ): ?array {
		$queryArray          = [];
		$queryArray['email'] = strtolower( $email );

		if ( $this->getCampaignId() ) {
			$queryArray['campaignId'] = $this->getCampaignId();
		}

		return $this->getByQuery( ( new Query( $queryArray ) )->doExactMatch() );
	}

	public static function getBasePath(): string {

		return 'contacts';

	}

	/**
	 * @param Contact $contact
	 * @param bool $blacklist
	 * @param int|null $messageId
	 * @param \Closure|null $deleteFailedCallback
	 *
	 * @return bool|null
	 */
	public function deleteByContact( Contact $contact, bool $blacklist = true, ?int $messageId = null, ?\Closure $deleteFailedCallback = null ): ?bool {
		$query       = [];
		$queryString = '';
		if ( isset( $messageId ) ) {
			$query['messageId'] = $messageId;
		}
		if ( ! $blacklist ) {
			$query['reason'] = 'api';
		}
		if ( count( $query ) ) {
			$queryString = '?' . http_build_query( $query );
		}

		return $this->deleteById( $contact->getContactId() . $queryString, $deleteFailedCallback );
	}
}