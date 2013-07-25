<?php
/**
 * Production configution file
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
return CMap::mergeArray(
	require_once(dirname(__FILE__).'/main.php'),
	array(
		'components' => array(
			'cache'=>array(
				'class'=>'system.caching.CFileCache'
			),
		)
	)
);

?>