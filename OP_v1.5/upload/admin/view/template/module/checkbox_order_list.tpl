<script src='view/javascript/checkbox/jquery2/jquery-2.1.1.min.js'></script>
<script>
    var jq2_1_1 = jQuery.noConflict();
</script>

<script type="text/javascript" src="view/javascript/checkbox/bootstrap/js/bootstrap.min.js"></script>
<link href="view/stylesheet/checkbox/bootstrap.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/checkbox/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />

<div class="checkbox-rro-order-list-container">
  <button id="button-checkbox-rro-order-list-container" title="" data-toggle="collapse" data-target="#collapseCheckboxRroOrderList"
          aria-expanded="false" aria-controls="collapseCheckboxRroOrderList" class="btn btn-default"
          style="margin-bottom: 15px"><i class="fa fa-print"></i>
    Checkbox PPO
  </button>

  <div class="collapse" id="collapseCheckboxRroOrderList">
    <div class="card checkbox-rro-order-list-card-body card-body page-header">

      <div id="checkbox-rro-before-load">
        <i class="fa fa-spinner fa-spin"></i>
      </div>


    </div>
  </div>
</div>

<script>
    $(document).ready(function () {

        $(document).on('click', '#button-checkbox-rro-order-list-container, .checkbox-rro-order-list-button-refresh', function () {

            $('#checkbox-rro-before-load').addClass('preloader');

            $.get('index.php?route=module/checkbox/orderListContainer&token=<?php echo $token; ?>', function(html) {

                $('#checkbox-rro-before-load').removeClass('preloader');

                $('#collapseCheckboxRroOrderList .card-body').html(html);

            });
        });


        $(document).on('click', '.js-checkbox-rro-order-create-shift', function () {

            $('#checkbox-rro-before-load').addClass('preloader');

            $.get('index.php?route=module/checkbox/createCashierShift&token=<?php echo $token; ?>', function(html) {

                $('#checkbox-rro-before-load').removeClass('preloader');

                if (html['error']) {
                    $('#collapseCheckboxRroOrderList .card-body').prepend(html['error']);
                } else {
                    $('.checkbox-rro-order-list-button-refresh').click();
                }

            });
        });

        $(document).on('click', '.js-checkbox-rro-order-close-shift', function () {

            $('#checkbox-rro-before-load').addClass('preloader');

            $.get('index.php?route=module/checkbox/closeCashierShift&token=<?php echo $token; ?>', function(html) {

                $('#checkbox-rro-before-load').removeClass('preloader');

                if (html['error']) {
                    $('#collapseCheckboxRroOrderList .card-body').prepend(html['error']);
                } else {
                    $('.checkbox-rro-order-list-button-refresh').click();
                }

            });
        });

        $(document).on('click', '.checkbox-rro-order-list-button-service-cash', function () {

            $('#checkbox-rro-before-load').addClass('preloader');

            let cash = $('.checkbox-rro-order-list-input-service-cash').val();
            $.get('index.php?route=module/checkbox/createServiceReceipt&token=<?php echo $token; ?>&cash=' + cash, function(html) {

                $('#checkbox-rro-before-load').removeClass('preloader');

                if (html['error']) {
                    $('#collapseCheckboxRroOrderList .card-body').prepend(html['error']);
                } else {
                    $('.checkbox-rro-order-list-button-refresh').click();
                }

            });
        });

    });
</script>
<style>
    .checkbox-rro-order-list-card-body {
        position: relative;
        min-height: 100px;
    }

    #checkbox-rro-before-load {
        display: none;
    }

    #checkbox-rro-before-load.preloader {
        display: block;
        position: absolute;
        left: 0;
        top: 0;
        right: 50%;
        bottom: 0;
        z-index: 1001;
        background-color: #f0f8ff9c;
    }

    #checkbox-rro-before-load.preloader i {
        font-size: 70px;
        position: absolute;
        left: 50%;
        top: 50%;
        margin: -35px 0 0 -35px;
    }
</style>
