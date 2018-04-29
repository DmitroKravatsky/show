<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bid Entities';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="product">
    <div class="container">
        <div class="breadcrumb-box">
            <div class="nav-wrapper">
                <?php foreach ($bids as $bid) :?>
                <?php //echo "<pre>"; var_dump($bid->id); exit;?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            Bid id : <?php echo $bid->id ?>
                            <a class="btn btn-default" href="<?= Url::to(['bid/detail',
                            'id' => $bid->id])?> ">Details &raquo;
                            </a>
                        </div>
                        <div class="panel-footer">
                            Bid creator id : <?php echo $bid->created_by ?>
                        </div>
                        <div class="panel-footer">
                            Bid creator name : <?php echo $bid->name ?>
                        </div>
                        <div class="panel-footer">
                            Bid creator last_name : <?php echo $bid->last_name ?>
                        </div>
                        <div class="panel-footer">
                            Bid creator phone_number : <?php echo $bid->email ?>
                        </div>
                        <div class="panel-footer">
                            Bid creator last_name : <?php echo $bid->phone_number ?>
                        </div>
                        <div class="panel-footer">
                            Bid id : <?php echo $bid->from_payment_system ?>
                        </div>
                        <div class="panel-footer">
                            Bid id : <?php echo $bid->to_payment_system ?>
                        </div>
                        <div class="panel-footer">
                            Bid id : <?php echo $bid->from_wallet ?>
                        </div>
                        <div class="panel-footer">
                            Bid id : <?php echo $bid->to_wallet ?>
                        </div>
                        <div class="panel-footer">
                            Bid id : <?php echo $bid->from_sum ?>
                        </div>
                        <div class="panel-footer">
                            Bid id : <?php echo $bid->to_sum ?>
                        </div>
                        <div class="panel-footer">
                            Bid id : <?php echo $bid->from_currency ?>
                        </div>
                        <div class="panel-footer">
                            Bid id : <?php echo $bid->to_currency ?>
                        </div>
                    </div>
                <?php endforeach ?>

            </div>
        </div>
    </div>
</section>
