<?php


namespace GetResponse\ValueObject;


use GetResponse\ContactsApi;

class Contact extends Base {
	protected $contactId;
	protected $email;
	protected $note;
	protected $origin;
	protected $dayOfCycle;
	protected $changedOn;
	protected $timeZone;
	protected $ipAddress;
	protected $scoring;
	protected $activities;
	protected $tags;
	/**
	 * @var CustomFields
	 */
	protected $customFields;
	/**
	 * @var Campaign
	 */
	protected $campaign;

	public function toArray(): array {
		$returnArray = [
			'campaign' => $this->getCampaign()->toArray(),
			'email'    => $this->getEmail()
		];

		if ( $this->getName() ) {
			$returnArray['name'] = $this->getName();
		}
		if ( $this->getIpAddress() ) {
			$returnArray['ipAddress'] = $this->getIpAddress();
		}
		if ( $this->getDayOfCycle() ) {
			$returnArray['dayOfCycle'] = $this->getDayOfCycle();
		}
		if ( $this->getNote() ) {
			$returnArray['note'] = $this->getNote();
		}
		if ( $this->getScoring() ) {
			$returnArray['scoring'] = $this->getScoring();
		}
		if ( $this->getTags()->getTags() ) {
			$returnArray = array_merge( $returnArray, $this->getTags()->toArray() );
		}

		if ( $this->getCustomFields()->getCustomFields() ) {
			$returnArray = array_merge( $returnArray, $this->getCustomFields()->toArray() );
		}

		return $returnArray;
	}

	/**
	 * @param string $contactId
	 *
	 * @return Contact
	 */
	public function setContactId( string $contactId ): Contact {
		$this->contactId = $contactId;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getContactId(): ?string {
		return $this->contactId;
	}

	/**
	 * @param Campaign $campaign
	 *
	 * @return Contact
	 */
	public function setCampaign( Campaign $campaign ): Contact {
		$this->campaign = $campaign;

		return $this;
	}

	/**
	 * @return Campaign
	 * @throws \GetResponse\Exception
	 */
	public function getCampaign(): Campaign {
		return $this->campaign ?? $this->campaign = new Campaign();
	}

	/**
	 * @param string $campaignId
	 *
	 * @return Contact
	 * @throws \GetResponse\Exception
	 */
	public function setCampaignId( string $campaignId ): Contact {
		$this->getCampaign()->setCampaignId( $campaignId );

		return $this;
	}

	/**
	 * @return string|null
	 * @throws \GetResponse\Exception
	 */
	public function getCampaignId(): ?string {
		return $this->getCampaign()->getCampaignId();
	}

	/**
	 * @param string $email
	 *
	 * @return Contact
	 */
	public function setEmail( string $email ): Contact {
		$this->email = strtolower( $email );

		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail(): ?string {
		return $this->email;
	}

	/**
	 * @param string $note
	 *
	 * @return Contact
	 */
	public function setNote( string $note ): Contact {
		$this->note = $note;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getNote(): ?string {
		return $this->note;
	}

	/**
	 * @param string $origin
	 *
	 * @return Contact
	 */
	public function setOrigin( string $origin ): Contact {
		$this->origin = $origin;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOrigin(): ?string {
		return $this->origin;
	}

	/**
	 * @param string $dayOfCycle
	 *
	 * @return Contact
	 */
	public function setDayOfCycle( string $dayOfCycle ): Contact {
		$this->dayOfCycle = $dayOfCycle;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDayOfCycle(): ?string {
		return $this->dayOfCycle;
	}

	/**
	 * @param string $changedOn
	 *
	 * @return Contact
	 */
	public function setChangedOn( string $changedOn ): Contact {
		$this->changedOn = $changedOn;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getChangedOn(): ?\DateTime {
		if ( $this->changedOn ) {
			return new DateTime( $this->changedOn );
		}

		return null;
	}

	/**
	 * @param string $timeZone
	 *
	 * @return Contact
	 */
	public function setTimeZone( string $timeZone ): Contact {
		$this->timeZone = $timeZone;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTimeZone(): ?string {
		return $this->timeZone;
	}

	/**
	 * @param string $ipAddress
	 *
	 * @return Contact
	 */
	public function setIpAddress( string $ipAddress ): Contact {
		$this->ipAddress = $ipAddress;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getIpAddress(): ?string {
		return $this->ipAddress;
	}

	/**
	 * @param null $activities
	 *
	 * @return Contact
	 */
	public function setActivities( $activities ) {
		$this->activities = $activities;

		return $this;
	}

	/**
	 * @return null
	 */
	public function getActivities() {
		return $this->activities;
	}

	/**
	 * @param CustomFields $customFields
	 *
	 * @return Contact
	 */
	public function setCustomFields( CustomFields $customFields ): Contact {
		$this->customFields = $customFields;

		return $this;
	}

	/**
	 * @return CustomFields
	 * @throws \GetResponse\Exception
	 */
	public function getCustomFields(): CustomFields {
		return $this->customFields ?? $this->customFields = new CustomFields();
	}

	/**
	 * @param int $scoring
	 *
	 * @return Contact
	 */
	public function setScoring( int $scoring ): Contact {
		$this->scoring = $scoring;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getScoring(): ?int {
		return $this->scoring;
	}

	/**
	 * @param Tags $tags
	 *
	 * @return Contact
	 */
	public function setTags( Tags $tags ): Contact {
		$this->tags = $tags;

		return $this;
	}

	/**
	 * @return Tags
	 */
	public function getTags(): Tags {
		return $this->tags ?? $this->tags = new Tags();
	}

	protected function mapData( \stdClass $data ): void {
		if ( isset( $data->campaign ) ) {
			$this->setCampaign( new Campaign( $data->campaign ) );
		}

		if ( isset( $data->customFieldValues ) && count( $data->customFieldValues ) ) {
			$this->setCustomFields( new CustomFields( $data->customFieldValues ) );
		}

		if ( isset( $data->tags ) && count( $data->tags ) ) {
			$this->setTags( new Tags( $data->tags ) );
		}
		/**
		 *   object(stdClass)#7 (15) {
		 * ["contactId"]=>
		 * string(4) "kaXj"
		 * ["href"]=>
		 * string(48) "https://api3.getresponse360.com/v3/contacts/kaXj"
		 * ["name"]=>
		 * string(15) "Johnny Saunders"
		 * ["email"]=>
		 * string(21) "john@yettimedia.co.uk"
		 * ["note"]=>
		 * NULL
		 * ["origin"]=>
		 * string(3) "api"
		 * ["dayOfCycle"]=>
		 * string(1) "2"
		 * ["changedOn"]=>
		 * string(24) "2019-08-03T22:48:06+0000"
		 * ["timeZone"]=>
		 * string(13) "Europe/London"
		 * ["ipAddress"]=>
		 * string(14) "37.235.122.254"
		 * ["activities"]=>
		 * string(59) "https://api3.getresponse360.com/v3/contacts/kaXj/activities"
		 * ["campaign"]=>
		 * object(stdClass)#8 (3) {
		 * ["campaignId"]=>
		 * string(1) "z"
		 * ["href"]=>
		 * string(46) "https://api3.getresponse360.com/v3/campaigns/z"
		 * ["name"]=>
		 * string(6) "20cogs"
		 * }
		 * ["createdOn"]=>
		 * string(24) "2019-08-03T22:47:11+0000"
		 * ["scoring"]=>
		 * NULL
		 * ["engagementScore"]=>
		 * int(5)
		 * }
		 */
	}

	protected function getApiClass(): string {
		return ContactsApi::class;
	}
}