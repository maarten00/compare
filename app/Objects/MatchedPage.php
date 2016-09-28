<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kuiper (maarten@dtcmedia.nl)
 * Date: 23/09/2016
 * Time: 20:40
 */

namespace App\Objects;

class MatchedPage {

	public $id, $title, $type, $percentage;

	/**
	 * ProcessedPage constructor.
	 *
	 * @param $id
	 * @param $title
	 * @param $type
	 * @param $percentage
	 */
	public function __construct( $id, $title, $type, $percentage ) {
		$this->id         = $id;
		$this->title      = $title;
		$this->type       = $type;
		$this->percentage = $percentage;
	}

}