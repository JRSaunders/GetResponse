<?php


namespace GetResponse;


class Href extends GetResponseApi {
	/**
	 * @return string
	 */
	public static function getBasePath(): string {
		return '';
	}

	/**
	 * @param $href
	 *
	 * @return \CurlRequest
	 * @throws Exception
	 */
	public function callHref( $href ) {
		return $this->getCurlRequestHref( $href, null )->get()->getDecodedJsonResponse();
	}
}