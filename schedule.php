<?php

class schedule
{

	private static $schedule;

	public function __construct()
	{
		self::loadSchedule();
	}

	public static function getSchedule()
	{
		if (null === self::$schedule) self::loadSchedule();

		return self::$schedule;
	}

	/**
	 * Loads the schedule data off the disk and stores it statically
	 */
	private static function loadSchedule()
	{
		self::$schedule = json_decode(file_get_contents('schedule.json'));
	}

	/**
	 * Stores the schedule to disk
	 */
	private function storeSchedule()
	{
		file_put_contents('schedule.json', json_encode(self::$schedule));

		// Sleeping 1/10s to avoid race conditions and reloading the file
		usleep(100000);
		self::loadSchedule();
	}

	/**
	 * Kicks off the update schedule process which will build out any missing dates and remove old ones
	 */
	public function updateSchedule()
	{
		// Removing old days if we have any days
		if (count(self::$schedule) > 0) {
			$currDate = date('Y/m/d');

			while (isset(self::$schedule[0]) && self::$schedule[0]->date != $currDate) {
				array_shift(self::$schedule);
			}
		}

		// Building out the schedule if we're missing days
		if (count(self::$schedule) < 7) $this->build();
	}

	/**
	 * Builds out missing days into the schedule
	 */
	private function build()
	{
		$locations = locations::getLocations();
		$currDate = date('Y/m/d');

		$nextDate = strtotime($currDate) + (86400*count(self::$schedule));

		while (count(self::$schedule) < 7) {

			$day = new stdClass();

			$day->date = date('Y/m/d',$nextDate);
			$day->location = $locations[rand(0, max(0, (count($locations)-1)))];

			self::$schedule[] = $day;

			$nextDate += 86400;

		}

		$this->storeSchedule();
	}

}