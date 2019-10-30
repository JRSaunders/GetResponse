<?php


namespace GetResponse\ValueObject;

/**
 * Class Tags
 * @package GetResponse\ValueObject
 */
class Tags implements ToArray {
	/**
	 * @var Tag[]
	 */
	protected $tags = [];

	public function __construct( ?array $tags = null ) {
		$this->setTags( $tags );
	}

	/**
	 * @param array|null $tags
	 *
	 * @return Tags
	 * @throws \GetResponse\Exception
	 */
	public function setTags( ? array $tags ): Tags {
		if ( ! $tags ) {
			return $this;
		}
		foreach ( $tags as &$tag ) {
			if ( ( ! $tag instanceof Tag ) && is_object( $tag ) ) {
				$tag = new Tag( $tag );
			}
			$this->addTag( $tag );
		}

		return $this;
	}

	/**
	 * @param Tag $tag
	 *
	 * @return Tags
	 */
	public function addTag( Tag $tag ): Tags {
		if ( ! $tag->getTagId() ) {
			return $this;
		}
		if ( $dupe = $this->getTagById( $tag->getTagId() ) ) {
			$this->unsetTagById( $tag->getTagId() );
		}
		$this->tags[] = $tag;

		return $this;
	}

	public function getTagById( string $id ): ?Tag {
		foreach ( $this->getTags() as &$tag ) {
			if ( $tag->getTagId() == $id ) {
				return $tag;
			}
		}

		return null;
	}

	/**
	 * @return Tag[]
	 */
	public function getTags(): array {
		return $this->tags;
	}

	/**
	 * @param $id
	 *
	 * @return bool
	 */
	public function unsetTagById( $id ): bool {
		foreach ( $this->getTags() as $key => $tag ) {
			if ( $tag->getTagId() == $id ) {
				unset( $this->tags[ $key ] );

				return true;
			}
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function toArray(): array {
		$returnArray         = [];
		$returnArray['tags'] = [];
		foreach ( $this->getTags() as $tag ) {
			$returnArray['tags'][] = $tag->toArray();
		}

		return $returnArray;
	}
}