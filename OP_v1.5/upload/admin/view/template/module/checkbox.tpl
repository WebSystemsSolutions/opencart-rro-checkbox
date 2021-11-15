<?php echo  $header; ?>
<script src='view/javascript/checkbox/jquery2/jquery-2.1.1.min.js'></script>
<script>
    var jq2_1_1 = jQuery.noConflict();
</script>

<script type="text/javascript" src="view/javascript/checkbox/bootstrap/js/bootstrap.min.js"></script>
<link href="view/stylesheet/checkbox/bootstrap.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/checkbox/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-checkbox" data-toggle="tooltip" title="<?php echo  $button_save; ?>"
                class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo  $cancel; ?>" data-toggle="tooltip" title="<?php echo  $button_cancel; ?>"
           class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo  $heading_title; ?></h1>
      <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo  $breadcrumb['href']; ?>"><?php echo  $breadcrumb['text']; ?></a></li>
          <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
      <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo  $error_warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo  $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo  $action; ?>" method="post" enctype="multipart/form-data" id="form-checkbox"
              class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-checkbox_rro_login"><?php echo  $entry_rro_login; ?></label>
            <div class="col-sm-10">
              <input type="text" name="checkbox_rro_login" value="<?php echo  $checkbox_rro_login; ?>"
                     placeholder="<?php echo  $entry_rro_login; ?>"
                     id="input-checkbox_rro_login" class="form-control"/>

            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-checkbox_rro_password"><?php echo  $entry_rro_password; ?></label>
            <div class="col-sm-10">
              <input type="text" name="checkbox_rro_password" value="<?php echo  $checkbox_rro_password; ?>"
                     placeholder="<?php echo  $entry_rro_password; ?>"
                     id="input-checkbox_rro_password" class="form-control"/>

            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-checkbox_rro_cashbox_key"><?php echo  $entry_rro_cashbox_key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="checkbox_rro_cashbox_key" value="<?php echo  $checkbox_rro_cashbox_key; ?>"
                     placeholder="<?php echo  $entry_rro_cashbox_key; ?>"
                     id="input-checkbox_rro_cashbox_key" class="form-control"/>

            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-checkbox_rro_is_dev">DEV режим</label>
            <div class="col-sm-10">
              <select name="checkbox_rro_is_dev" id="input-checkbox_rro_is_dev" class="form-control">
                  <?php if ($checkbox_rro_is_dev) { ?>
                    <option value="1" selected="selected"><?php echo  $text_enabled; ?></option>
                    <option value="0"><?php echo  $text_disabled; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo  $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo  $text_disabled; ?></option>
                  <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo  $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="checkbox_status" id="input-status" class="form-control">
                  <?php if ($checkbox_status) { ?>
                    <option value="1" selected="selected"><?php echo  $text_enabled; ?></option>
                    <option value="0"><?php echo  $text_disabled; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo  $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo  $text_disabled; ?></option>
                  <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-checkbox_rro_receipt_header"><?php echo  $entry_rro_receipt_header; ?></label>
            <div class="col-sm-10">
              <input type="text" name="checkbox_rro_receipt_header" value="<?php echo  $checkbox_rro_receipt_header; ?>"
                     placeholder="<?php echo  $entry_rro_receipt_header; ?>"
                     id="input-checkbox_rro_receipt_header" class="form-control"/>

            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-checkbox_rro_receipt_footer"><?php echo  $entry_rro_receipt_footer; ?></label>
            <div class="col-sm-10">
              <input type="text" name="checkbox_rro_receipt_footer" value="<?php echo  $checkbox_rro_receipt_footer; ?>"
                     placeholder="<?php echo  $entry_rro_receipt_footer; ?>"
                     id="input-checkbox_rro_receipt_footer" class="form-control"/>

            </div>
          </div>

          <pre>
            <?php print_r($instruction) ?>
          </pre>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo  $footer; ?>
