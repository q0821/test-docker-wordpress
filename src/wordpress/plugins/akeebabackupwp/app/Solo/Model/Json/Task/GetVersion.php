<?php
/**
 * @package   solo
 * @copyright Copyright (c)2014-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Solo\Model\Json\Task;

use Awf\Registry\Registry;
use Solo\Model\Json\TaskInterface;
use Solo\Model\Update;

/**
 * Get the version information of Akeeba Solo
 */
class GetVersion implements TaskInterface
{
	/**
	 * Return the JSON API task's name ("method" name). Remote clients will use it to call us.
	 *
	 * @return  string
	 */
	public function getMethodName()
	{
		return 'getVersion';
	}

	/**
	 * Execute the JSON API task
	 *
	 * @param   array $parameters The parameters to this task
	 *
	 * @return  mixed
	 *
	 * @throws  \RuntimeException  In case of an error
	 */
	public function execute(array $parameters = array())
	{
		$update = new Update();

		$updateInformation = $update->getUpdateInformation();

		if (is_object($updateInformation) && ($updateInformation instanceof Registry))
		{
			$updateInformation = $updateInformation->toArray();
		}

		if (is_array($updateInformation) && array_key_exists('releasenotes', $updateInformation))
		{
			unset ($updateInformation['releasenotes']);
		}

		$edition = AKEEBABACKUP_PRO ? 'pro' : 'core';

		return (object)array(
			'api'        => AKEEBA_JSON_API_VERSION,
			'component'  => AKEEBABACKUP_VERSION,
			'date'       => AKEEBABACKUP_DATE,
			'edition'    => $edition,
			'updateinfo' => $updateInformation,
		);
	}
}
