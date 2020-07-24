<?php

namespace arambalakjian\DataObjectAsPage\Decorators;

use Silverstripe\ORM\DataExtension;

class VersionedDataObjectAsPage extends DataExtension{

	private static $summary_fields = [
		'Status' => 'Status'
    ];

	private static $versioning = [
		"Stage",  "Live"
    ];
}
