<?= $header; ?><?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-checkbox" data-toggle="tooltip" title="<?= $button_save; ?>"
                class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>"
           class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?= $heading_title; ?></h1>
      <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
          <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
      <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= $error_warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?= $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-checkbox"
              class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-checkbox_rro_login"><?= $entry_rro_login; ?></label>
            <div class="col-sm-10">
              <input type="text" name="checkbox_rro_login" value="<?= $checkbox_rro_login; ?>"
                     placeholder="<?= $entry_rro_login; ?>"
                     id="input-checkbox_rro_login" class="form-control"/>

            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-checkbox_rro_password"><?= $entry_rro_password; ?></label>
            <div class="col-sm-10">
              <input type="text" name="checkbox_rro_password" value="<?= $checkbox_rro_password; ?>"
                     placeholder="<?= $entry_rro_password; ?>"
                     id="input-checkbox_rro_password" class="form-control"/>

            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-checkbox_rro_cashbox_key"><?= $entry_rro_cashbox_key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="checkbox_rro_cashbox_key" value="<?= $checkbox_rro_cashbox_key; ?>"
                     placeholder="<?= $entry_rro_cashbox_key; ?>"
                     id="input-checkbox_rro_cashbox_key" class="form-control"/>

            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-checkbox_rro_is_dev">DEV режим</label>
            <div class="col-sm-10">
              <select name="checkbox_rro_is_dev" id="input-checkbox_rro_is_dev" class="form-control">
                  <?php if ($checkbox_rro_is_dev) { ?>
                    <option value="1" selected="selected"><?= $text_enabled; ?></option>
                    <option value="0"><?= $text_disabled; ?></option>
                  <?php } else { ?>
                    <option value="1"><?= $text_enabled; ?></option>
                    <option value="0" selected="selected"><?= $text_disabled; ?></option>
                  <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?= $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="checkbox_status" id="input-status" class="form-control">
                  <?php if ($checkbox_status) { ?>
                    <option value="1" selected="selected"><?= $text_enabled; ?></option>
                    <option value="0"><?= $text_disabled; ?></option>
                  <?php } else { ?>
                    <option value="1"><?= $text_enabled; ?></option>
                    <option value="0" selected="selected"><?= $text_disabled; ?></option>
                  <?php } ?>
              </select>
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
<?= $footer; ?>
