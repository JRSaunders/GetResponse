<?php


namespace GetResponse;

/**
 * Class CustomFieldsApi
 * @package GetResponse
 */
class CustomFieldsApi extends GetResponseApi {

	public static function getBasePath(): string {
		return 'custom-fields';
	}

	public function getByName(string $name): ?array{

			$queryArray          = [];
			$queryArray['name'] =  $name ;

			return $this->getByQuery( ( new Query( $queryArray ) )->doExactMatch() );

	}
}