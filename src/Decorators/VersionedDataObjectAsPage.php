<?php

namespace arambalakjian\DataObjectAsPage\Decorators;

use Silverstripe\ORM\DataExtension;

class VersionedDataObjectAsPage extends DataExtension{

	private static $summary_fields = array(
		'Status' => 'Status'
	);

	private static $versioning = array(
		"Stage",  "Live"
	);
}
