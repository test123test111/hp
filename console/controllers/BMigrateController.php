<?php
namespace console\controllers;

use yii\console\controllers\MigrateController as Mig;
class BMigrateController extends Mig
{
    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection to use
     * when applying migrations. Starting from version 2.0.3, this can also be a configuration array
     * for creating the object.
     */
    public $db = 'backenddb';
}
