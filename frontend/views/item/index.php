<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $items yii\data\ActiveDataProvider */
/* @var $servers yii\data\ActiveDataProvider */
/* @var $item common\models\Item */

$this->title = Yii::t('frontend', 'Items');
?>

<div class="row">
<div class="col-sm-9 item-index">

    <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
    <form action="" method="$_GET">
        <label>
            <input type="checkbox" id="http" name="http" <?php echo (Yii::$app->request->get('http')=='on') ? 'checked' : ''; ?>>
            Only http
        </label>
        <button>Filter</button>
        <?= Html::a('Clear', ['item/index']); ?>
    </form>
    <?php
    foreach ($items->models as $item) {
        echo $this->render('shortView', [
            'model' => $item
        ]);
    }
    ?>

    <div>
        <?= LinkPager::widget([
            'pagination' => $items->getPagination()
        ]) ?>
    </div>
</div>

<div class="col-sm-3 blog-sidebar">
    <h1><?= Yii::t('frontend', 'Servers') ?></h1>
    <ul>
    <?php
    foreach ($servers->models as $server) {
        echo $this->render('//server/shortViewServer', [
            'model' => $server
        ]);
    }
    ?>
    </ul>
</div>
</div>