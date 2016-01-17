<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;

class PeopleWriteToVisit extends Event {

	use SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
    public $sched;

	public function __construct($sched)
	{
		$this->sched = $sched;
	}

}
