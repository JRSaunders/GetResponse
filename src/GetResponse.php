<?php


namespace GetResponse;

/**
 * Class GetResponse
 * @package GetResponse
 */
class GetResponse {

	protected $apiKey;
	protected $domain;
	protected $campaignId;
	protected $facets = [];
	static $instance;

	/**
	 * GetResponse constructor.
	 *
	 * @param string $apiKey
	 * @param string|null $domain
	 * @param string|null $campaignId
	 */
	public function __construct( string $apiKey, ?string $domain, ?string $campaignId ) {
		$this->setApiKey( $apiKey )->setDomain( $domain )->setCampaignId( $campaignId );
		static::$instance = $this;
	}


	/**
	 * @return GetResponse
	 * @throws Exception
	 */
	public static function getInstance(): GetResponse {
		if ( static::$instance instanceof GetResponse ) {
			return static::$instance;
		}
		throw new Exception( 'No Get Response Instance Available' );
	}

	/**
	 * @return string
	 */
	public function getApiKey(): string {
		return $this->apiKey;
	}

	/**
	 * @param string $apiKey
	 *
	 * @return GetResponse
	 */
	public function setApiKey( string $apiKey ): GetResponse {
		$this->apiKey = $apiKey;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getDomain(): ?string {
		return $this->domain;
	}

	/**
	 * @param string|null $domain
	 *
	 * @return GetResponse
	 */
	public function setDomain( ?string $domain ): GetResponse {
		$this->domain = $domain;

		return $this;
	}

	/**
	 * @param $class
	 *
	 * @return GetResponseApi
	 * @throws Exception
	 */
	public function getFacetOrCreate( $class ): GetResponseApi {
		try {
			if ( isset( $this->facets[ $class ] ) ) {
				return $this->facets[ $class ];
			}
			return $this->facets[ $class ] = new $class( $this->getApiKey(), $this->getDomain() );
		} catch ( \Exception | \Error | \TypeError $exception ) {
			throw new Exception( $exception->getMessage(), $exception->getCode() );
		}
	}

	/**
	 * @return ContactsApi
	 * @throws Exception
	 */
	public function getContacts(): ContactsApi {
		/**
		 * @var $contacts ContactsApi
		 */
		$contacts = $this->getFacetOrCreate( ContactsApi::class );

		return $contacts->setCampaignId( $this->getCampaignId() );
	}

	/**
	 * @return CustomFieldsApi
	 * @throws Exception
	 */
	public function getCustomFields(): CustomFieldsApi {
		return $this->getFacetOrCreate( CustomFieldsApi::class );
	}

	/**
	 * @return CampaignsApi
	 * @throws Exception
	 */
	public function getCampaigns(): CampaignsApi {
		return $this->getFacetOrCreate( CampaignsApi::class );
	}

	/**
	 * @return string|null
	 */
	public function getCampaignId(): ?string {
		return $this->campaignId;
	}

	/**
	 * @param string|null $campaignId
	 *
	 * @return GetResponse
	 */
	public function setCampaignId( ?string $campaignId ): GetResponse {
		$this->campaignId = $campaignId;

		return $this;
	}

	/**
	 * @return TagsApi
	 * @throws Exception
	 */
	public function getTags(): TagsApi {
		return $this->getFacetOrCreate( TagsApi::class );
	}

	/**
	 * @return GetResponseApi
	 * @throws Exception
	 */
	public function getHref() {
		return $this->getFacetOrCreate( Href::class );
	}

}