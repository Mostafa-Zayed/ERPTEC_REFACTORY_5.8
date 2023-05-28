<?php $request = app('Illuminate\Http\Request'); ?>

<?php $pos_layout = is_pos_layout();?>
<?php $business = get_business(); ?>

<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale(), false); ?>" dir="<?php echo e(in_array(session()->get('user.language', config('app.locale')), config('constants.langs_rtl')) ? 'rtl' : 'ltr', false); ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="keywords" content="ERP TEC"/>
        <meta name="description" content="">
		<meta name="author" content="">
		<meta property=”og:title” content=”” />
        <meta property=”og:url” content=”https://erptec.net/erp” />
        <meta property=”og:type” content=”website” />
        <meta property=”og:description” content=”” />
        <meta property=”og:image” content=”” />
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="<?php echo e(csrf_token(), false); ?>">
        <link rel="icon" href="<?php echo e(asset('website/images/logo_test.png'), false); ?>" type="image/gif" sizes="16x16">

        <title><?php echo $__env->yieldContent('title'); ?> - <?php echo e(Session::get('business.name'), false); ?></title>
        
		
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;900&display=swap" rel="stylesheet">
        
        <?php echo $__env->make('layouts.partials.css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <link rel="stylesheet" href="<?php echo e(asset('css/style-v1.css'), false); ?>">
        
        <?php echo $__env->yieldContent('css'); ?>
    </head>

    <body class="<?php if($pos_layout): ?> hold-transition lockscreen <?php else: ?> hold-transition skin-<?php echo e(get_business_theme_color(), false); ?> sidebar-mini <?php endif; ?>">
        <div class="wrapper thetop">
            <script type="text/javascript">
                if(localStorage.getItem("upos_sidebar_collapse") == 'true'){
                    var body = document.getElementsByTagName("body")[0];
                    body.className += " sidebar-collapse";
                }
            </script>
            <?php if(!$pos_layout): ?>
                <?php echo $__env->make('layouts.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo $__env->make('layouts.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php else: ?>
                <?php echo $__env->make('layouts.partials.header-pos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="<?php if(!$pos_layout): ?> content-wrapper <?php endif; ?>">
                <!-- empty div for vuejs -->
                <div id="app">
                    <?php echo $__env->yieldContent('vue'); ?>
                </div>
                <!-- Add currency related field-->
                <input type="hidden" id="__code" value="<?php echo e(session('currency')['code'], false); ?>">
                <input type="hidden" id="__symbol" value="<?php echo e(session('currency')['symbol'], false); ?>">
                <input type="hidden" id="__thousand" value="<?php echo e(session('currency')['thousand_separator'], false); ?>">
                <input type="hidden" id="__decimal" value="<?php echo e(session('currency')['decimal_separator'], false); ?>">
                <input type="hidden" id="__symbol_placement" value="<?php echo e(session('business.currency_symbol_placement'), false); ?>">
                <input type="hidden" id="__precision" value="<?php echo e(config('constants.currency_precision', 2), false); ?>">
                <input type="hidden" id="__quantity_precision" value="<?php echo e(config('constants.quantity_precision', 2), false); ?>">
                <!-- End of currency related field-->

                <?php if(session('status')): ?>
                    <input type="hidden" id="status_span" data-status="<?php echo e(session('status.success'), false); ?>" data-msg="<?php echo e(session('status.msg'), false); ?>">
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>

                <div class='scrolltop no-print'>
                    <div class='scroll icon'><i class="fas fa-angle-up"></i></div>
                </div>

                <?php if(config('constants.iraqi_selling_price_adjustment')): ?>
                    <input type="hidden" id="iraqi_selling_price_adjustment">
                <?php endif; ?>

                <!-- This will be printed -->
                <section class="invoice print_section" id="receipt_section">
                </section>
                
            </div>
            <?php echo $__env->make('home.todays_profit_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <!-- /.content-wrapper -->

            <?php if(!$pos_layout): ?>
                <?php echo $__env->make('layouts.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php else: ?>
                <?php echo $__env->make('layouts.partials.footer_pos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            <audio id="success-audio">
              <source src="<?php echo e(asset('audio/success.ogg?v=' . $asset_v), false); ?>" type="audio/ogg">
              <source src="<?php echo e(asset('audio/success.mp3?v=' . $asset_v), false); ?>" type="audio/mpeg">
            </audio>
            <audio id="error-audio">
              <source src="<?php echo e(asset('audio/error.ogg?v=' . $asset_v), false); ?>" type="audio/ogg">
              <source src="<?php echo e(asset('audio/error.mp3?v=' . $asset_v), false); ?>" type="audio/mpeg">
            </audio>
            <audio id="warning-audio">
              <source src="<?php echo e(asset('audio/warning.ogg?v=' . $asset_v), false); ?>" type="audio/ogg">
              <source src="<?php echo e(asset('audio/warning.mp3?v=' . $asset_v), false); ?>" type="audio/mpeg">
            </audio>
        </div>

        <?php if(!empty($__additional_html)): ?>
            <?php echo $__additional_html; ?>

        <?php endif; ?>

        <?php echo $__env->make('layouts.partials.javascripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="modal fade view_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel"></div>

        <?php if(!empty($__additional_views) && is_array($__additional_views)): ?>
            <?php $__currentLoopData = $__additional_views; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $additional_view): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if ($__env->exists($additional_view)) echo $__env->make($additional_view, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        
        <script>
            $(document).ready(function(){
                
                // Change Language App
                $(".selectors").on('change',function () {
                    window.location = $(this).val();
                });
            });
        </script>
    </body>

</html><?php /**PATH F:\ERPTEC_REFACTORY_5.8\resources\views/layouts/app.blade.php ENDPATH**/ ?>