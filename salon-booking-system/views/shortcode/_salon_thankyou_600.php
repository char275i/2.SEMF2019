<div class="col-xs-12">
    <?php if($confirmation) : ?>
        <h2 class="salon-step-title"><?php _e('Booking Status', 'salon-booking-system') ?></h2>
    <?php else : ?>
        <?php
        $args = array(
            'label'        => __('Booking Confirmation', 'salon-booking-system'),
            'tag'          => 'h2',
            'textClasses'  => 'salon-step-title',
            'inputClasses' => '',
            'tagClasses'   => 'salon-step-title',
        );
        echo $plugin->loadView('shortcode/_editable_snippet', $args);
        ?>
    <?php endif ?>

    <?php include '_errors.php'; ?>

    <?php if (isset($payOp) && $payOp == 'cancel'): ?>

        <div class="alert alert-danger">
            <p><?php _e('The payment is failed, please try again.', 'salon-booking-system') ?></p>
        </div>

    <?php else: ?>

	<?php include '_salon_thankyou_okbox.php' ?>

	<?php $ppl = false; ?>
        <?php include '_salon_thankyou_alert.php' ?>
    <?php endif ?>
</div>
<div class="col-xs-12 sln-form-actions-wrapper sln-input--action">
    <div class="sln-form-actions sln-payment-actions row">
        <?php if($paymentMethod): ?>
            <div class="col-xs-12 col-sm-6">
                <div class="sln-btn sln-btn--emphasis sln-btn--noheight sln-btn--fullwidth">
                    <?php echo $paymentMethod->renderPayButton(compact('booking', 'paymentMethod', 'ajaxData', 'payUrl')); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if($paymentMethod && $payLater): ?>
            <div class="col-xs-12 col-sm-6">
                <div class="row">
                    <div class="col-xs-12 col-md-8 pull-right">
                        <a  href="<?php echo $laterUrl ?>" class="sln-btn sln-btn--emphasis sln-btn--noheight sln-btn--fullwidth"
                            <?php if($ajaxEnabled): ?>
                                data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
                            <?php endif ?>>
                            <?php _e('I\'ll pay later', 'salon-booking-system') ?>
                        </a>
                    </div>
                    <div class="col-xs-12 col-md-4 pull-right">
                        <h4><?php _e('Or', 'salon-booking-system') ?></h4>
                    </div>
                </div>
            </div>
        <?php elseif(!$paymentMethod) : ?>
        <div class="sln-form-actions sln-payment-actions row">
            <div class="col-xs-12 col-sm-6 col-md-8 pull-right">
                <a  href="<?php echo $laterUrl ?>" class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth"
                    <?php if($ajaxEnabled): ?>
                        data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
                    <?php endif ?>>
                    <?php _e('Complete', 'salon-booking-system') ?>
                </a>
            </div>
            <?php endif ?>
        </div>
    </div>
</div>
