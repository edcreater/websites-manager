<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * Template model.
 *
 * @property string $id
 * @property string $job_id
 * @property string $item_id
 * @property string $check_status
 * @property string $check_date
 */
class Check extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%check}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['item_id'], 'required'],
            [['item_id'], 'integer'],
            [['job_id', 'check_status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'job_id' => Yii::t('backend', 'Job id'),
            'item_id' => Yii::t('backend', 'Item id'),
            'check_status' => Yii::t('backend', 'Status'),
            'check_date' => Yii::t('backend', 'Date'),
        ];
    }

    /**
     * Return checks list
     *
     * @return ActiveDataProvider
     */
    public static function findChecks(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Check::find(),
            'pagination' => false
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public static function findById(int $id): Check
    {
        if (($model = Check::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested item does not exist.');
    }
}
