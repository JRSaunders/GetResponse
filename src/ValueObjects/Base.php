<?php


namespace GetResponse\ValueObject;

use GetResponse\GetResponse;

/**
 * Class Base
 * @package GetResponse\ValueObject
 */
abstract class Base implements ToArray {
	/**
	 * @var string | null
	 */
	protected $href;
	/**
	 * @var string | null
	 */
	protected $name;

	/**
	 * Base constructor.
	 *
	 * @param \stdClass|null $data
	 * @param string|null $id
	 *
	 * @throws \GetResponse\Exception
	 */
	public function __construct( ?\stdClass $data = null, ?string $id = null ) {
		if ( $id ) {
			$this->baseMapData( $this->getDataByIdFromInstance( $id ) );
		} else {
			$this->baseMapData( $data );
		}
	}

	/**
	 * @param \stdClass|null $data
	 */
	protected function baseMapData( ?\stdClass $data ): void {
		if ( is_object( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( is_callable( [ $this, strtolower( 'set' . $key ) ] ) ) {
					try {
						call_user_func_array( [ $this, 'set' . $key ], [ $value ] );
					} catch ( \TypeError $type_error ) {
						// do nothing
					}
				}
			}
			$this->mapData( $data );
		}
	}

	abstract protected function mapData( \stdClass $data ): void;

	/**
	 * @param string $id
	 *
	 * @return \stdClass|null
	 * @throws \GetResponse\Exception
	 */
	protected function getDataByIdFromInstance( string $id ): ?\stdClass {

		return GetResponse::getInstance()->getFacetOrCreate( static::getApiClass() )->getById( $id );
	}

	abstract protected function getApiClass(): string;

	/**
	 * @return string
	 */
	public function getHref(): ?string {
		return $this->href;
	}

	/**
	 * @param string $href
	 *
	 * @return Base
	 */
	public function setHref( string $href ): Base {
		$this->href = $href;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): ?string {
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return Base
	 */
	public function setName( string $name ): Base {
		$this->name = $name;

		return $this;
	}

	/**
	 * @throws \GetResponse\Exception
	 */
	public function callHref() {
		return GetResponse::getInstance()->getHref()->callHref( $this->getHref() );
	}

}