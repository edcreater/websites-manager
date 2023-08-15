<?php
/**
 * Created by Ed.Creater <ed.creater@gmail.com>.
 * Author Site: http://codesweet.ru
 * Date: 14.03.2017
 */

use yii\helpers\Html;

$search_query = Yii::$app->request->post('search_query')
?>

<?php echo Html::beginForm(['/item/search'], 'post'); ?>
    <div class="navbar-form">

        <?= Html::textInput('search_query', $search_query, ['placeholder'=>'Find domain...', 'class'=>'form-control']); ?>

        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>

    </div>
<?php echo Html::endForm(); ?>
