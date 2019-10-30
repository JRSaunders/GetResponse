<?php


namespace GetResponse\ValueObject;


trait ValidTrait {
	protected $valid = false;

	/**
	 * @return bool
	 */
	public function isValid(): bool {
		return $this->valid;
	}
}