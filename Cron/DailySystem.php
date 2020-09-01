<?php

namespace Hunters\Bazaarvoice\Cron;

use Hunters\Bazaarvoice\Service\AddProductDatabase;


class DailySystem {

	/**
	 * @var AddProductDatabase
	 */
	private $AddProductDatabase;

    /**
     * DailySystem constructor.
	 * @param AddProductDatabase                   $AddProductDatabase
     */
    public function __construct(
		AddProductDatabase  $AddProductDatabase
    ) {
        $this->AddProductDatabase = $AddProductDatabase;
    }
	
    public function execute() {
        $this->AddProductDatabase->apibazaarvoice();
    }
	
}
