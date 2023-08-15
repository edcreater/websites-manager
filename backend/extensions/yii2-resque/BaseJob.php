<?php
/**
 * Created by PhpStorm.
 * User: echo
 * Date: 10/17/15
 * Time: 5:22 PM
 */

namespace edcreater\yii2resque;


interface BaseJob
{

    public function setUp();

    public function perform($args, $job);

    public function tearDown();
}
