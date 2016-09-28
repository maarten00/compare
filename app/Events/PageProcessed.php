<?php

namespace App\Events;

use app\Objects\PageResults;
use app\Objects\ProcessedPage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PageProcessed implements ShouldBroadcast {
	use InteractsWithSockets, SerializesModels;
	/**
	 * @var ProcessedPage
	 */
	public $page;
	/**
	 * @var PageResults
	 */
	public $result;

	/**
	 * Create a new event instance.
	 *
	 * @param ProcessedPage $page
	 * @param PageResults   $result
	 */
	public function __construct( ProcessedPage $page, PageResults $result ) {
		$this->page   = $page;
		$this->result = $result;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return Channel|array
	 */
	public function broadcastOn() {
		return [ 'processedpages' ];
	}
}
