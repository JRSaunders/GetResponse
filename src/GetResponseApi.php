<?php


namespace GetResponse;

/**
 * Class GetResponseApi
 * @package GetResponse
 */
abstract class GetResponseApi {
	protected $apiKey;
	protected $version = 'v3';
	protected $domain;
	protected $url = 'https://api.getresponse.com';
	/**
	 * @var Exception | null
	 */
	protected $exception;
	/**
	 * @var CurlRequest | null
	 */
	protected $lastCurl;

	public function __construct( string $apiKey, ?string $domain ) {
		$this->setApiKey( $apiKey )->setDomain( $domain );
	}

	abstract public static function getBasePath(): string;

	/**
	 * @return string
	 */
	protected function getApiKey(): string {
		return $this->apiKey;
	}

	/**
	 * @param string $apiKey
	 *
	 * @return GetResponseApi
	 */
	public function setApiKey( string $apiKey ): GetResponseApi {
		$this->apiKey = $apiKey;

		return $this;
	}

	/**
	 * @return string|null
	 */
	protected function getDomain(): ?string {
		return $this->domain;
	}

	/**
	 * @param string|null $domain
	 *
	 * @return GetResponseApi
	 */
	public function setDomain( ?string $domain ): GetResponseApi {
		$this->domain = $domain;

		return $this;
	}

	/**
	 * @return array
	 */
	private function getHeaderArray(): array {
		$authHeader = [
			'X-Auth-Token' => "api-key {$this->getApiKey()}",
		];
		if ( $this->getDomain() ) {
			$domain                 = trim( str_replace( [ 'http://', 'https://', 'www.' ], '', $this->getDomain() ) );
			$authHeader['X-Domain'] = $domain;
		}

		return $authHeader;
	}

	/**
	 * /**
	 * @param string $path
	 * @param array|null $data
	 *
	 * @return CurlRequest
	 * @throws Exception
	 */
	protected function getCurlRequest( string $path, ?array $data ): CurlRequest {
		$path = ltrim( $path, '/' );

		return $this->getCurlRequestHref( $this->url . '/' . $this->version . '/' . static::getBasePath() . '/' . $path, $data );
	}

	/**
	 * @param string $href
	 * @param array|null $data
	 *
	 * @return CurlRequest
	 * @throws Exception
	 */
	protected function getCurlRequestHref( string $href, ?array $data ): CurlRequest {
		$this->setException( null );
		$curl = ( new CurlRequest( $href, $data ) )
			->setHeaders( $this->getHeaderArray() )->setHandleAnyErrorFunction( function ( CurlRequest $request ) {
				$resp = $request->getDecodedJsonResponse();
				if ( isset( $resp->message ) ) {
					$message = $resp->message;
					if ( isset( $resp->context ) ) {
						if ( is_array( $resp->context ) ) {
							$message .= ' - ' . join( ' > ', $resp->context );
						} else {
							$message .= ' - ' . $resp->context;
						}
					}
					throw new Exception( $message, $request->getErrorNumber() );
				} else {
					throw new Exception( 'GR API Failed ' . static::class, $request->getErrorNumber() );
				}

			} );
		$this->setLastCurl( $curl );

		return $curl;
	}

	/**
	 * @param Page|null $page
	 *
	 * @return array|null
	 */
	public function getAll( ?Page $page = null ): ?array {
		if ( ! isset( $page ) ) {
			$page = new Page( 1, 1000 );
		}
		try {
			return $this->getCurlRequest( '', $page->toArray() )->get()->getDecodedJsonResponse();
		} catch ( Exception $exception ) {
			$this->setException( $exception );
		}

		return null;
	}

	/**
	 * @param string $id
	 *
	 * @return \stdClass|null
	 */
	public function getById( string $id ): ?\stdClass {
		try {
			return $this->getCurlRequest( $id, null )->getDecodedJsonResponse();
		} catch ( Exception $exception ) {
			$this->setException( $exception );
		}

		return null;
	}


	/**
	 * @param Query $query
	 *
	 * @return mixed|null
	 */
	public function getByQuery( Query $query ) {
		try {
			return $this->getCurlRequest( '', $query->toArray() )->get()->getDecodedJsonResponse();
		} catch ( Exception $exception ) {
			$this->setException( $exception );
		}

		return null;
	}

	/**
	 * @param string $id
	 * @param \Closure|null $deleteFailedCallback
	 *
	 * @return bool|null
	 */
	public function deleteById( string $id, ?\Closure $deleteFailedCallback = null ): ?bool {
		try {
			$curl = $this->getCurlRequest( $id, null )->delete();
			$curl->getResponse();

			return ( ! $curl->isHttpCodeError() );
		} catch ( Exception $exception ) {
			$this->setException( $exception );
			if ( $deleteFailedCallback ) {
				return (bool) call_user_func_array( $deleteFailedCallback, [ $exception, $this ] );
			}
		}

		return null;
	}

	/**
	 * @param string $id
	 * @param array $data
	 * @param \Closure|null $updateFailedCallback
	 *
	 * @return \stdClass|null
	 */
	public function updateById( string $id, array $data, ?\Closure $updateFailedCallback = null ): ?bool {
		try {
			return (bool) $this->getCurlRequest( $id, $data )->post()->jsonEncodeSendData()->getDecodedJsonResponse();
		} catch ( Exception $exception ) {
			$this->setException( $exception );
			if ( $updateFailedCallback ) {
				return (bool) call_user_func_array( $updateFailedCallback, [ $exception, $this ] );
			}
		}

		return null;
	}

	/**
	 * @param array $data
	 * @param \Closure|null $createFailedCallback
	 *
	 * @return bool|null
	 */
	public function create( array $data, ?\Closure $createFailedCallback = null ): ?bool {
		try {
			$curl = $this->getCurlRequest( '', $data )->post()->jsonEncodeSendData();
			$curl->getResponse();

			return (bool) ( ! $curl->isHttpCodeError() );
		} catch ( Exception $exception ) {
			$this->setException( $exception );
			if ( $createFailedCallback ) {
				return call_user_func_array( $createFailedCallback, [ $exception, $this ] );
			}
		}

		return null;
	}

	/**
	 * @return Exception|null
	 */
	public function getException(): ?Exception {
		return $this->exception;
	}

	/**
	 * @param Exception|null $exception
	 *
	 * @return GetResponseApi
	 */
	public function setException( ?Exception $exception ): GetResponseApi {
		$this->exception = $exception;

		return $this;
	}

	/**
	 * @param CurlRequest|null $lastCurl
	 *
	 * @return GetResponseApi
	 */
	public function setLastCurl( ?CurlRequest $lastCurl ): GetResponseApi {
		$this->lastCurl = $lastCurl;

		return $this;
	}

	/**
	 * @return CurlRequest|null
	 */
	public function getLastCurl(): ?CurlRequest {
		return $this->lastCurl;
	}


}