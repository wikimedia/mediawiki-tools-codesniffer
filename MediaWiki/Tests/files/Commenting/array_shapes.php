<?php

class ArrayShapes {

	/** @var array{float, float} */
	private array $tuple = [ 0.0, 0.0 ];

	/** @var array{lat: float, lon: float} */
	private array $coord = [ 'lat' => 0.0, 'lon' => 0.0 ];

	/**
	 * @param array{float, float} $tuple
	 * @return array{float, float}
	 */
	public function tuple( $tuple ) {
		return $tuple;
	}

	/**
	 * @param array{lat: float, lon: float} $coord
	 * @return array{lat: float, lon: float}
	 */
	public function coord( $coord ) {
		return $coord;
	}

}
