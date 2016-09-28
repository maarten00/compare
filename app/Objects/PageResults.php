<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kuiper (maarten@dtcmedia.nl)
 * Date: 23/09/2016
 * Time: 20:38
 */

namespace App\Objects;

class PageResults {

	public $pages;

	/**
	 * PageResults constructor.
	 *
	 * @param $pages
	 */
	public function __construct( array $pages ) {
		$this->pages = $pages;
	}

}