<<<<<<< HEAD
<?php

namespace common\components\check;

use common\models\Check;
use common\models\Item;
use yii\base\Component;
use yii\data\ActiveDataProvider;

class WorkerCheck implements \edcreater\yii2resque\BaseJob
{
    private $item_id;
    private $url;

    /**
     * Event triggered after save
     */
    const EVENT_AFTER_SAVE = 'afterSave';

    public function setUp()
    {
        # Set up environment for this job
    }

    public function perform($args, $job)
    {

        $this->setArgs($args);

        stream_context_set_default(
            [
                'http' => [
                    'method' => 'HEAD'
                ]
            ]
        );
        $urlStatus = $this->getHttpResponseCode($this->url . '/wp-sitemap.xml');

        if ($urlStatus !== '200') {

            $html = "
                    <p>Site: {$args['url']}</p>
                    <p>Status: {$urlStatus}</p>
                ";
            \Yii::$app->mailer->compose()
                ->setFrom('from@domain.com')
                ->setTo('to@domain.com')
                ->setSubject('Проверка провалена')
                ->setHtmlBody('<b>текст сообщения в формате HTML</b>')
                ->send();

        }


        $check = new Check();
        $check->item_id = $this->item_id;
        $check->job_id = $job->getId();
        $check->check_status = $urlStatus;
        $check->save();
    }

    public function tearDown()
    {
        # Remove environment for this job
        $interval = 5 * 60; # This job will repeat every 5 minutes
        $time = time() + $interval;

        # Add next job queue based on interval
        \Yii::$app->resque->enqueueJob($time, 'common\components\check\WorkerCheck', ['item_id' => $this->item_id, 'url' => $this->url]);
    }

    function getHttpResponseCode($theURL)
    {
        $headers = get_headers($theURL);
        return substr($headers[0], 9, 3);
    }

    function setArgs($args)
    {
        $this->item_id = $args['item_id'];
        $this->url = rtrim($args['url'], '/\\');
    }
}
=======
<?php

namespace common\components\check;

use common\models\Check;
use common\models\Item;
use yii\base\Component;
use yii\data\ActiveDataProvider;

class WorkerCheck implements \edcreater\yii2resque\BaseJob
{
    private $item_id;
    private $url;

    /**
     * Event triggered after save
     */
    const EVENT_AFTER_SAVE = 'afterSave';

    public function setUp()
    {
        # Set up environment for this job
    }

    public function perform($args, $job)
    {

        $this->setArgs($args);

        stream_context_set_default(
            [
                'http' => [
                    'method' => 'HEAD'
                ]
            ]
        );
        $urlStatus = $this->getHttpResponseCode($this->url . '/wp-sitemap.xml');

        if ($urlStatus !== '200') {

            $html = "
                    <p>Site: {$args['url']}</p>
                    <p>Status: {$urlStatus}</p>
                ";
            \Yii::$app->mailer->compose()
                ->setFrom('from@domain.com')
                ->setTo('to@domain.com')
                ->setSubject('Проверка провалена')
                ->setHtmlBody('<b>текст сообщения в формате HTML</b>')
                ->send();

        }


        $check = new Check();
        $check->item_id = $this->item_id;
        $check->job_id = $job->getId();
        $check->check_status = $urlStatus;
        $check->save();
    }

    public function tearDown()
    {
        # Remove environment for this job
        $interval = 5 * 60; # This job will repeat every 5 minutes
        $time = time() + $interval;

        # Add next job queue based on interval
        \Yii::$app->resque->enqueueJob($time, 'common\components\check\WorkerCheck', ['item_id' => $this->item_id, 'url' => $this->url]);
    }

    function getHttpResponseCode($theURL)
    {
        $headers = get_headers($theURL);
        return substr($headers[0], 9, 3);
    }

    function setArgs($args)
    {
        $this->item_id = $args['item_id'];
        $this->url = rtrim($args['url'], '/\\');
    }
}
>>>>>>> 8dba4bce3c311d8886fe3363a3cc09f3f22bb930
