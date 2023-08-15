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
 * @property string $title
 * @property string $link
 * @property Item[] $items
 */
class Template extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['link'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Title'),
            'link' => Yii::t('backend', 'Link'),
        ];
    }

    /**
     * Return items for a template
     *
     * @return ActiveDataProvider
     */
    public function getItems(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Item::find()
                ->where([
                    'template_id' => $this->id,
                    'publish_status' => Item::STATUS_PUBLISH
                ])
        ]);
    }

    /**
     * Return templates list
     *
     * @return ActiveDataProvider
     */
    public static function findTemplates(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Template::find(),
            'pagination' => false
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public static function findById(int $id): Template
    {
        if (($model = Template::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested item does not exist.');
    }
}
