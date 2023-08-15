<?php

namespace frontend\controllers;

use common\models\Server;
use common\models\Template;
use common\models\User;
use common\models\UserItemRelation;
use frontend\models\ItemForm;
use frontend\models\search\ItemSearch;
use Yii;
use common\models\Item;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

class ItemController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'search', 'add-user', 'check-start'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {

        $filter_https = Yii::$app->request->get('http');

        if ($filter_https == 'on') {
            $items = new ActiveDataProvider([
                'query' => Item::find()
                    ->where(['protocol' => 'http'])
                    ->orderBy(['publish_date' => SORT_DESC]),
            ]);

            $items->setPagination([
                'pageSize' => Yii::$app->params['pageSize']
            ]);
    
            return $this->render('index', [
                'items' => $items,
                'servers' => Server::findServers()
            ]);
        }

        //$items = Item::findByCurrentUser();
        $items = Item::findPublished();
        $items->setPagination([
            'pageSize' => Yii::$app->params['pageSize']
        ]);

        return $this->render('index', [
            'items' => $items,
            'servers' => Server::findServers()
        ]);
    }

    public function actionView(int $id): string
    {
        $acceptedUsers = UserItemRelation::findUsersByItem($id);

        return $this->render('view', [
            'model' => Item::findById($id),
            'acceptedUsers' => $acceptedUsers,
            'userItemRelation' => new UserItemRelation(),
            'users' => User::find()->all(),
        ]);
    }

    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionSearch()
    {
        $request = Yii::$app->request;
        $search_query = $request->post('search_query');

        $searchModel = new ItemSearch();
        $items = $searchModel->search($search_query);

        return $this->render('search', [
            'items' => $items,
            'search_query' => ($search_query) ? $search_query : '',
        ]);
    }

    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAddUser()
    {
        $model = new UserItemRelation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->save()) {
                return $this->redirect(['index']);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCheckStart($id)
    {
        $model = Item::findById($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->save()) {
                return $this->redirect(['index']);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCheckStart($id)
    {
        $model = Item::findById($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->save()) {
                return $this->redirect(['index']);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

    }
}
