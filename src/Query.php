<?php


namespace GetResponse;


use GetResponse\ValueObject\ToArray;

class Query implements ToArray {

	/**
	 *
	 * query[email]
	 * string
	 * Search contacts by email
	 *
	 * query[name]
	 * string
	 * Search contacts by name
	 *
	 * query[createdOn][from]
	 * string <date> or string <date-time> (DateOrDateTime)
	 * Example: "2018-04-15" "2018-01-15T13:30:42+0000"
	 * Count data from this date
	 *
	 * query[createdOn][to]
	 * string <date> or string <date-time> (DateOrDateTime)
	 * Example: "2018-04-15" "2018-01-15T13:30:42+0000"
	 * Count data to this date
	 *
	 * sort[email]
	 * string (SortOrderEnum)
	 * Enum:"ASC" "DESC"
	 * Sort contacts by email
	 *
	 * sort[name]
	 * string (SortOrderEnum)
	 * Enum:"ASC" "DESC"
	 * Sort contacts by name
	 *
	 * sort[createdOn]
	 * string (SortOrderEnum)
	 * Enum:"ASC" "DESC"
	 * Sort contacts by creation date
	 *
	 * fields
	 * string
	 * List of fields that should be returned. Id is always returned. Fields should be separated by comma
	 *
	 * perPage
	 * integer <int32> [ 1 .. 1000 ]
	 * Default: 100
	 * Requested number of results per page
	 *
	 * page
	 */

	/**
	 * @var array
	 */
	protected $queryArray = [];
	protected $page = 1;
	protected $limit = 100;
	protected $exactMatch = false;
	/**
	 * @var array|null
	 */
	protected $fields;

	public function __construct( array $queryArray ) {
		$this->setQueryArray( $queryArray );
	}

	/**
	 * @return array
	 */
	public function getQueryArray(): array {
		return $this->queryArray;
	}

	/**
	 * @param array $queryArray
	 *
	 * @return Query
	 */
	public function setQueryArray( array $queryArray ): Query {
		$this->queryArray = $queryArray;

		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray(): array {
		$returnArray = [
			'query'   => $this->getQueryArray(),
			'page'    => $this->getPage(),
			'perPage' => $this->getLimit()
		];
		if ( $this->getFields() ) {
			$returnArray['fields'] = $this->getFields();
		}
		if($this->isExactMatch()){
			$returnArray['additionalFlags'] = 'exactMatch';
		}
		return $returnArray;
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
	 * @return Query
	 */
	public function setPage( int $page ): Query {
		$this->page = $page;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getLimit(): int {
		return $this->limit;
	}

	/**
	 * @param int $limit
	 *
	 * @return Query
	 */
	public function setLimit( int $limit ): Query {
		$this->limit = $limit;

		return $this;
	}

	/**
	 * @return array|null
	 */
	public function getFields(): ?array {
		return $this->fields;
	}

	/**
	 * @param array|null $fields
	 *
	 * @return Query
	 */
	public function setFields( ?array $fields ): Query {
		$this->fields = $fields;

		return $this;
	}

	/**
	 * @return bool
	 */
	protected function isExactMatch(): bool {
		return $this->exactMatch;
	}

	/**
	 * @param bool $exactMatch
	 *
	 * @return Query
	 */
	public function setExactMatch( bool $exactMatch ): Query {
		$this->exactMatch = $exactMatch;

		return $this;
	}

	/**
	 * @return Query
	 */
	public function doExactMatch(): Query {
		$this->setExactMatch( true );

		return $this;
	}


}