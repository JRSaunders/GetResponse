<?php


namespace GetResponse\ValueObject;


use GetResponse\ContactsApi;
use GetResponse\GetResponse;

/**
 * Class ContactByEmail
 * @package GetResponse\ValueObject
 */
class ContactByEmail extends Contact {

	use ValidTrait;

	/**
	 * ContactByEmail constructor.
	 *
	 * @param string $email
	 * @param string|null $campaignId
	 * @param bool $withCustomFields
	 *
	 * @throws \GetResponse\Exception
	 */
	public function __construct( string $email, ?string $campaignId = null, bool $withCustomFields = true ) {
		/**
		 * @var $contactsApi ContactsApi
		 */
		$contactsApi = GetResponse::getInstance()->getFacetOrCreate( static::getApiClass() );
		if ( ! isset( $campaignId ) ) {
			$campaignId = GetResponse::getInstance()->getCampaignId();
			$contactsApi->setCampaignId( $campaignId );
		}
		$contactsApi->setCampaignId( $campaignId );
		$contactData = $contactsApi->getByEmail( $email );
		if ( $contactData && count( $contactData ) && $withCustomFields ) {
			$this->baseMapData( $contactsApi->getById( ( new Contact( $contactData[0] ) )->getContactId() ) );
			$this->valid = true;
		} else if ( $contactData && count( $contactData ) && ! $withCustomFields ) {
			$this->setCampaignId( $campaignId );
			$this->baseMapData( $contactData[0] );
			$this->valid = true;
		}


	}

}