<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Post model.
 *
 * @property string $id
 * @property string $parent_id
 * @property string $protocol
 * @property string $domain
 * @property string $admin_link
 * @property string $content
 * @property string $server_id
 * @property string $server_user_id
 * @property string $template_id
 * @property integer $check_enabled
 * @property string $author_id
 * @property string $publish_status
 * @property string $publish_date
 *
 * @property Item $parent
 * @property Item $childs
 * @property User $author
 * @property Server $server
 * @property ServerUser $serverUser
 * @property Template $template
 * @property Check $lastCheck
 */
class Item extends ActiveRecord
{

    public const STATUS_PUBLISH = 'publish';
<<<<<<< HEAD
    public const STATUS_DRAFT   = 'draft';
    private $task_sended        = false;
=======
    public const STATUS_DRAFT = 'draft';
    private $task_sended = false;
>>>>>>> 8dba4bce3c311d8886fe3363a3cc09f3f22bb930

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
<<<<<<< HEAD
        return array(
            array( array( 'domain', 'protocol' ), 'required' ),
            array( array( 'parent_id', 'server_id', 'server_user_id', 'template_id', 'author_id' ), 'integer' ),
            array( array( 'admin_link', 'content', 'publish_status' ), 'string' ),
            array( array( 'publish_date' ), 'safe' ),
            array(
                array( 'protocol' ),
                'string',
                'max' => 8,
            ),
            array(
                array( 'domain' ),
                'string',
                'max' => 255,
            ),
            array( array( 'check_enabled' ), 'integer' ),
        );
=======
        return [
            [['domain', 'protocol'], 'required'],
            [['server_id', 'server_user_id', 'template_id', 'author_id'], 'integer'],
            [['admin_link', 'content', 'publish_status'], 'string'],
            [['publish_date'], 'safe'],
            [['protocol'], 'string', 'max' => 8],
            [['domain'], 'string', 'max' => 255],
            [['check_enabled'], 'integer'],
        ];
>>>>>>> 8dba4bce3c311d8886fe3363a3cc09f3f22bb930
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return array(
            'id'             => Yii::t('backend', 'ID'),
            'parent_id'             => Yii::t('backend', 'Parent ID'),
            'domain'         => Yii::t('backend', 'Domain'),
            'protocol'       => Yii::t('backend', 'Protocol'),
            'admin_link'     => Yii::t('backend', 'Admin link'),
            'content'        => Yii::t('backend', 'Content'),
            'server_id'      => Yii::t('backend', 'Server ID'),
            'server_user_id' => Yii::t('backend', 'Server User ID'),
            'server'         => Yii::t('backend', 'Server'),
            'server_user'    => Yii::t('backend', 'Server user'),
            'template_id'    => Yii::t('backend', 'Template ID'),
            'template'       => Yii::t('backend', 'Template'),
<<<<<<< HEAD
            'check_enabled'  => Yii::t('backend', 'Check enabled'),
=======
            'check_enabled'       => Yii::t('backend', 'Check enabled'),
>>>>>>> 8dba4bce3c311d8886fe3363a3cc09f3f22bb930
            'author'         => Yii::t('backend', 'Author'),
            'author_id'      => Yii::t('backend', 'Author ID'),
            'publish_status' => Yii::t('backend', 'Publish status'),
            'publish_date'   => Yii::t('backend', 'Publish date'),
        );
    }

    public function getParent(): ActiveQuery
    {
        return $this->hasMany(Item::class, array( 'id' => 'parent_id' ));
    }

    public function getChilds(): ActiveQuery
    {
        return $this->hasMany(Item::class, array( 'parent_id' => 'id' ));
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, array( 'id' => 'author_id' ));
    }

    public function getServer(): ActiveQuery
    {
        return $this->hasOne(Server::class, array( 'id' => 'server_id' ));
    }

    public function getServerUser(): ActiveQuery
    {
        return $this->hasOne(ServerUser::class, array( 'id' => 'server_user_id' ));
    }

    public function getTemplate(): ActiveQuery
    {
        return $this->hasOne(Template::class, array( 'id' => 'template_id' ));
    }

    public function getLastCheck(): ActiveQuery
    {
        return $this->hasOne(Check::class, array( 'item_id' => 'id' ));
    }

    public function getLastCheck(): ActiveQuery
    {
        return $this->hasOne(Check::class, ['item_id' => 'id']);
    }

    public static function findPublished(): ActiveDataProvider
    {
        return new ActiveDataProvider(
            array(
                'query' => self::find()
                    ->where(array( 'publish_status' => self::STATUS_PUBLISH ))
                    ->orderBy(array( 'publish_date' => SORT_DESC )),
            )
        );
    }

    public static function findByCurrentUser(): ActiveDataProvider
    {
        return new ActiveDataProvider(
            array(
                'query' => self::find()
                ->where(
                    array(
                        'publish_status' => self::STATUS_PUBLISH,
                        'author_id'      => Yii::$app->user->id,
                    )
                )
                ->orderBy(array( 'publish_date' => SORT_DESC )),
            )
        );
    }

    /**
     * @throws NotFoundHttpException
     */
    public static function findById(int $id, bool $ignorePublishStatus = false): Item
    {
        if (( $model = self::findOne($id) ) !== null) {
            if ($model->isPublished() || $ignorePublishStatus) {
                return $model;
            }
        }

        throw new NotFoundHttpException('The requested item does not exist.');
    }

    /**
     * @throws NotFoundHttpException
     */
    public static function findByIds(array $ids): array
    {
        if (( $items = self::findAll($ids) ) !== null) {
            return $items;
        }

        throw new NotFoundHttpException('The requested item does not exist.');
    }

    /**
     * @throws NotFoundHttpException
     */
    public function findByServerId(int $server_id, bool $ignorePublishStatus = false): array
    {
        if (( $items = self::find()->where(array( 'server_id' => $server_id ))->all() ) !== null) {
            return $items;
        }

        throw new NotFoundHttpException('The requested ServerUser does not exist.');
    }

    protected function isPublished(): bool
    {
        return $this->publish_status === self::STATUS_PUBLISH;
    }

<<<<<<< HEAD
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->check_enabled && ! $this->task_sended) {
=======
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if ($this->check_enabled && !$this->task_sended) {
>>>>>>> 8dba4bce3c311d8886fe3363a3cc09f3f22bb930
            $this->task_sended = true;

            $url = $this->protocol . '://' . $this->domain;

<<<<<<< HEAD
            // $queueName = 'check_' . $this->id;
            $job = Yii::$app->resque->runJob(
                'common\components\check\WorkerCheck',
                array(
                    'item_id' => $this->id,
                    'url'     => $url,
                )
            );
=======
            //$queueName = 'check_' . $this->id;
            $job = Yii::$app->resque->runJob('common\components\check\WorkerCheck', ['item_id' => $this->id, 'url' => $url]);

>>>>>>> 8dba4bce3c311d8886fe3363a3cc09f3f22bb930
        }
    }
}
