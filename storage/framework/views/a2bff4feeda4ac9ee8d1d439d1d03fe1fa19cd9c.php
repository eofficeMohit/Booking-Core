<?php $__env->startPush('css'); ?>
    <link href="<?php echo e(asset('module/booking/css/checkout.css?_ver=' . config('app.asset_version'))); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div id="payment-form"></div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
    <script src="<?php echo e(config('peach-payment.' . config('peach-payment.environment') . '.embedded_checkout_url')); ?>"></script>
    <script>
        const checkout = Checkout.initiate({
            key: "<?php echo e($entityId); ?>",
            checkoutId: "<?php echo e($checkoutId); ?>",
        });

        checkout.render("#payment-form");
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/BookingCore/modules/Booking/Views/frontend/gateways/peach.blade.php ENDPATH**/ ?>