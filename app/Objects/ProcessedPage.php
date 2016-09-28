<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kuiper (maarten@dtcmedia.nl)
 * Date: 23/09/2016
 * Time: 20:37
 */

namespace App\Objects;

class ProcessedPage {

	public $id, $title, $type;

	/**
	 * ProcessedPage constructor.
	 *
	 * @param $id
	 * @param $title
	 * @param $type
	 */
	public function __construct( $id, $title, $type ) {
		$this->id    = $id;
		$this->title = $title;
		$this->type  = $type;
	}

}