<?php


namespace GetResponse;


use GetResponse\ValueObject\ToArray;

/**
 * Class Page
 * @package GetResponse
 */
class Page implements ToArray {
	protected $perPage = 1000;
	protected $page = 1;

	/**
	 * Page constructor.
	 *
	 * @param int|null $page
	 * @param int|null $perPage
	 */
	public function __construct( ?int $page = null, ?int $perPage = null ) {
		if ( isset( $page ) ) {
			$this->setPage( $page );
		}
		if ( isset( $perPage ) ) {
			$this->setPerPage( $perPage );
		}
	}

	/**
	 * @return int
	 */
	public function getPerPage(): int {
		return $this->perPage;
	}

	/**
	 * @param int $perPage
	 *
	 * @return Page
	 */
	public function setPerPage( int $perPage ): Page {
		$this->perPage = $perPage;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPage(): int {
		return $this->page;
	}

	/**
	 * @param int $page
	 *
	 * @return Page
	 */
	public function setPage( int $page ): Page {
		$this->page = $page;

		return $this;
	}

	public function toArray(): array {
		return [ 'page' => $this->getPage(), 'perPage' => $this->getPerPage() ];
	}
}