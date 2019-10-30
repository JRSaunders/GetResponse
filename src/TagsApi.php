<?php


namespace GetResponse;

use GetResponse\ValueObject\Tag;

/**
 * Class TagsApi
 * @package GetResponse
 */
class TagsApi extends GetResponseApi {

	public static function getBasePath(): string {
		return 'tags';
	}

	public function updateByTag(Tag $tag){
		return $this->updateById( $tag->getTagId(), $tag->toArray());
	}
}