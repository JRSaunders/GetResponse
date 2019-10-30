<?php

namespace GetResponse\ValueObject;


use GetResponse\CampaignsApi;

class Campaign extends Base {

	protected $campaignId;

	public function toArray(): array {
		$returnArray = [];

		$returnArray['campaignId'] = $this->getCampaignId();
		if ( $this->getName() ) {
			$returnArray['name'] = $this->getName();
		}

		return $returnArray;
	}

	/**
	 * @return string
	 */
	public function getCampaignId(): ?string {
		return $this->campaignId;
	}

	/**
	 * @param string $campaignId
	 *
	 * @return Campaign
	 */
	public function setCampaignId( string $campaignId ): Campaign {
		$this->campaignId = $campaignId;

		return $this;
	}

	protected function mapData( \stdClass $data ): void {
		// TODO: Implement mapData() method.
	}

	protected function getApiClass(): string {
		return CampaignsApi::class;
	}
}