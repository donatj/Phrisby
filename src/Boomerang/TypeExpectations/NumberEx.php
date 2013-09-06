<?php

namespace Boomerang\TypeExpectations;

class NumberEx extends NumericEx {

	public function match( $data ) {
		return !is_string($data)
			   && parent::match($data);
	}

	public function getMatchingTypeName() {
		return 'int|float';
	}

}