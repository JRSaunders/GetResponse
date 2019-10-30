<?php


namespace GetResponse\ValueObject;


use GetResponse\TagsApi;

/**
 * Class Tag
 * @package GetResponse\ValueObject
 */
class Tag extends Base {
	protected $tagId;
	protected $createdAt;
	protected $color;

	/**
	 * @param string $tagId
	 *
	 * @return Tag
	 */
	public function setTagId( string $tagId ): Tag {
		$this->tagId = $tagId;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTagId(): ?string {
		return $this->tagId;
	}

	/**
	 * @param string $createdAt
	 *
	 * @return Tag
	 */
	public function setCreatedAt( string $createdAt ): Tag {
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCreatedAt(): ?string {
		return $this->createdAt;
	}

	/**
	 * @param string $color
	 *
	 * @return Tag
	 */
	public function setColor( string $color ): Tag {
		$this->color = $color;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getColor(): ?string {
		return $this->color;
	}

	protected function mapData( \stdClass $data ): void {
		// TODO: Implement mapData() method.
	}

	protected function getApiClass(): string {
		return TagsApi::class;
	}

	public function toArray(): array {
		$returnArray = [];
		if ( $this->getName() ) {
			$returnArray['name'] = $this->getName();
		}
		if ( $this->getTagId() ) {
			$returnArray['tagId'] = $this->getTagId();
		}

		return $returnArray;
	}
}