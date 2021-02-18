<div id="checkbox-rro-before-load">
  <i class="fa fa-spinner fa-spin"></i>
</div>

<div class="table-responsive">
  <table class="table table-bordered" style="width: 50%;">
    <tbody>
    <tr>
      <td>
        <h3>Статус каси</h3>
        *для роботи з касою запустіть
        <br> - Checkbox підпис або HSM (DepositSign)
      </td>
      <td><span class="checkbox-rro-order-status"><?= $status ?></span>
        <button class="btn btn-default checkbox-rro-order-list-button-refresh"><i class="fa fa-refresh"></i></button>
      </td>
    </tr>
    <tr>
      <td><h4>Відкрити / Закрити зміну</h4></td>
      <td>
          <?php if (!$is_connected) { ?>
            <button class="btn btn-success js-checkbox-rro-order-create-shift"><i class="fa fa-check fa-fw"></i> Відкрити
              зміну
            </button>
          <?php } else { ?>
            <button class="btn btn-danger js-checkbox-rro-order-close-shift"><i class="fa fa-ban fa-fw"></i> Закрити
              зміну
            </button>
          <?php } ?>
      </td>
    </tr>
    <tr>
      <td><h4>Друкувати z-звіт </h4>*за останньою закритою зміною</td>
      <td>
        <a target="_blank" href="<?= $link_z_report ?>" class="btn btn-info"><i class="fa fa-print fa-fw"></i> Друкувати z-звіт</a>
      </td>
    </tr>
    <tr>
      <td><h4>Службове внесення / винесення коштів </h4>*внесення зі знаком +<br>*винесення зі знаком -</td>
      <td>
        <p>Баланс: <?= $balance ?></p>
        <div class="col-md-9">
          <input type="number" class="form-control checkbox-rro-order-list-input-service-cash" value="">
        </div>
        <div class="col-md-3">
          <button class="btn btn-success checkbox-rro-order-list-button-service-cash"><i class="fa fa-money"></i></button>
        </div>
      </td>
    </tr>
    </tbody>
  </table>
</div>
