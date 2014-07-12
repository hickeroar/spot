<?php

class locations
{
	private static $locations = null;

	public function __construct()
	{
		self::loadLocations();
	}

	/**
	 * Returns the current array of locations
	 */
	public static function getLocations()
	{
		if (null === self::$locations) self::loadLocations();

		return self::$locations;
	}

	/**
	 * Loads the current locations from the disk and stores them statically
	 */
	private static function loadLocations()
	{
		self::$locations = json_decode(file_get_contents('locations.json'));
	}

	/**
	 * Stores the locations to disk
	 */
	private function storeLocations()
	{
		usort(self::$locations, array("self", "locSortCompare"));

		file_put_contents('locations.json', json_encode(self::$locations));

		// Sleeping 1/10s to avoid race conditions and reloading the file
		usleep(100000);
		self::loadLocations();
	}

	/**
	 * Backs up the locations
	 */
	private function archiveLocations()
	{
		file_put_contents('archive/'.date('Ymd').time().'_locations.json', json_encode(self::$locations));
	}

	/**
	 * Adds a new location to the array of locations
	 *
	 * @param string $zone
	 * @param string $location
	 * @param string $link
	 */
	public function add($zone, $location, $link)
	{
		// City and location must be set
		if (!trim($zone) || !trim($location)) return 'City Name and Specific Location are Required.';

		if (trim($link) != '' && !filter_var($link, FILTER_VALIDATE_URL)) return 'Specified Link is not a Valid URL.';

		$locationObject = new stdClass();

		$locationObject->zone = ucfirst(trim($zone));
		$locationObject->location = ucfirst(trim($location));
		$locationObject->link = trim($link);
		$locationObject->id = md5(microtime());

		self::archiveLocations();

		self::$locations[] = $locationObject;

		self::storeLocations();
	}

	private static function locSortCompare($a, $b)
	{
	    return strcasecmp($a->zone.$a->location, $b->zone.$b->location);
	}

}