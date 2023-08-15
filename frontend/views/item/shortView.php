<?php
/**
 * Created by PhpStorm.
 * User: georgy
 * Date: 09.07.14
 * Time: 9:26
 */
use yii\helpers\Html;
use common\models\Check;

/* @var $model common\models\Item */

$status = 'check disabled';
<<<<<<< HEAD
$class  = 'default';

if (! empty($model->lastCheck->check_status)) {
=======
$class = 'default';

if (!empty($model->lastCheck->check_status)) {
>>>>>>> 8dba4bce3c311d8886fe3363a3cc09f3f22bb930
    $status = $model->lastCheck->check_status;
    switch ($status) {
        case '200':
            $class = 'success';
            break;
        case '302':
            $class = 'warn';
            break;
        default:
            $class = 'danger';
            break;
    }
}
<<<<<<< HEAD

$has_subdomains = is_array($model->childs) && !empty($model->childs);
=======
>>>>>>> 8dba4bce3c311d8886fe3363a3cc09f3f22bb930
?>
<div class="item-card <?php echo ($has_subdomains) ? 'item-card--backgrounded' : ''; ?>">
<p class="item-card__heading">
    <?php
    echo Html::a(
        Yii::t('frontend', $model->domain),
        array(
            'item/view',
            'id' => $model->id,
        ),
        array( 'class' => 'item-card__title' )
    )
    ?>
    <a href="<?php echo $model->protocol . '://' . $model->domain; ?>" target="_blank" class="btn btn-success btn-xs">To site</a>
    <a href="<?php echo $model->protocol . '://' . $model->domain . $model->admin_link; ?>" target="_blank" class="btn btn-success btn-xs">To admin</a>
    <a href="<?php echo $model->server->link; ?>" target="_blank" class="btn btn-success btn-xs">To ispmanager</a>
</p>

<div class="meta">
<<<<<<< HEAD
    <p><?php echo Yii::t('frontend', 'Server'); ?>: 
        <?php
        echo Html::a(
            $model->server->title,
            array(
                'server/view',
                'id' => $model->server->id,
            )
        )
        ?>
    </p>
=======
    <p><?= Yii::t('frontend', 'Server') ?>: <?= Html::a($model->server->title, ['server/view', 'id' => $model->server->id]) ?></p>
>>>>>>> 8dba4bce3c311d8886fe3363a3cc09f3f22bb930
    <p class="check-status check-status--<?php echo $class; ?>">Status: <?php echo $status; ?></p>
</div>

</div>
