<div class="box" id="create-account">
  <div class="heading"><h1><?php echo language::translate('title_create_account', 'Create Account'); ?></h1></div>
  <div class="content">
    <?php echo functions::form_draw_form_begin('customer_form', 'post'); ?>
      <table>
        <tr>
          <td><?php echo language::translate('title_tax_id', 'Tax ID'); ?><br />
            <?php echo functions::form_draw_text_field('tax_id', true); ?></td>
          <td><?php echo language::translate('title_company', 'Company'); ?><br />
            <?php echo functions::form_draw_text_field('company', true); ?></td>
        </tr>
        <tr>
          <td><?php echo language::translate('title_firstname', 'First Name'); ?> <span class="required">*</span><br />
            <?php echo functions::form_draw_text_field('firstname', true); ?></td>
          <td><?php echo language::translate('title_lastname', 'Last Name'); ?> <span class="required">*</span><br />
            <?php echo functions::form_draw_text_field('lastname', true); ?></td>
        </tr>
        <tr>
          <td><?php echo language::translate('title_address1', 'Address 1'); ?> <span class="required">*</span><br />
            <?php echo functions::form_draw_text_field('address1', true); ?></td>
          <td><?php echo language::translate('title_address2', 'Address 2'); ?><br />
          <?php echo functions::form_draw_text_field('address2', true); ?></td>
        </tr>
        <tr>
          <td><?php echo language::translate('title_postcode', 'Postcode'); ?> <span class="required">*</span><br />
            <?php echo functions::form_draw_text_field('postcode', true); ?></td>
          <td><?php echo language::translate('title_city', 'City'); ?> <span class="required">*</span><br />
            <?php echo functions::form_draw_text_field('city', true); ?></td>
        </tr>
        <tr>
          <td><?php echo language::translate('title_country', 'Country'); ?> <span class="required">*</span><br />
            <?php echo functions::form_draw_countries_list('country_code', true); ?></td>
          <td><?php echo language::translate('title_zone_state_province', 'Zone/State/Province'); ?> <span class="required">*</span><br />
            <?php echo form_draw_zones_list(isset($_POST['country_code']) ? $_POST['country_code'] : '', 'zone_code', true); ?></td>
        </tr>
        <tr>
          <td><?php echo language::translate('title_email', 'E-mail'); ?> <span class="required">*</span><br />
            <?php echo functions::form_draw_email_field('email', true); ?></td>
          <td><?php echo language::translate('title_phone', 'Phone'); ?><br />
            <?php echo functions::form_draw_phone_field('phone', true); ?></td>
        </tr>
        <tr>
          <td><?php echo language::translate('title_newsletter', 'Newsletter'); ?><br />
            <label><?php echo functions::form_draw_checkbox('newsletter', '1', !empty($_POST) ? true : '1'); ?> <?php echo language::translate('title_subscribe', 'Subscribe'); ?></label></td>
          </td>
          <td></td>
        </tr>
        <tr>
          <td><?php echo language::translate('title_desired_password', 'Desired Password'); ?> <span class="required">*</span><br />
          <?php echo functions::form_draw_password_field('password', ''); ?></td>
          <td><?php echo language::translate('title_confirm_password', 'Confirm Password'); ?> <span class="required">*</span><br />
          <?php echo functions::form_draw_password_field('confirmed_password', ''); ?></td>
        </tr>
        <tr>
          <td colspan="2"><?php echo functions::form_draw_button('create_account', language::translate('title_create_account', 'Create Account')); ?></td>
        </tr>
      </table>
    <?php echo functions::form_draw_form_end(); ?>
  </div>
  
  <script>
    $("#box-checkout-account input, #box-checkout-account select").change(function() {
      if ($(this).val() == '') return;
      $('body').css('cursor', 'wait');
      $.ajax({
        url: '<?php echo document::ilink('ajax/get_address.json'); ?>?trigger='+$(this).attr('name'),
        type: 'post',
        data: $(this).closest('form').serialize(),
        cache: false,
        async: true,
        dataType: 'json',
        error: function(jqXHR, textStatus, errorThrown) {
          if (console) console.log(errorThrown.message)
        },
        success: function(data) {
          if (data['alert']) {
            alert(data['alert']);
          }
          $.each(data, function(key, value) {
            console.log(key +": "+ value);
            if ($("input[name='"+key+"']").length && $("input[name='"+key+"']").val() == '') $("input[name='"+key+"']").val(data[key]);
          });
        },
        complete: function() {
          $('body').css('cursor', 'auto');
        }
      });
    });
      
    $("form[name='customer_form'] input, form[name='customer_form'] select").change(function() {
      if ($(this).val() == '') return;
      $('body').css('cursor', 'wait');
      $.ajax({
        url: '<?php echo document::ilink('ajax/get_address.json'); ?>?trigger='+$(this).attr('name'),
        type: 'post',
        data: $(this).closest('form').serialize(),
        cache: false,
        async: true,
        dataType: 'json',
        error: function(jqXHR, textStatus, errorThrown) {
          if (console) console.warn(errorThrown.message);
        },
        success: function(data) {
          if (data['alert']) {
            alert(data['alert']);
            return;
          }
          $.each(data, function(key, value) {
            console.log(key +" "+ value);
            if ($("input[name='"+key+"']").length && $("input[name='"+key+"']").val() == '') $("input[name='"+key+"']").val(data[key]);
          });
        },
        complete: function() {
          $('body').css('cursor', 'auto');
        }
      });
    });
    
    $("select[name='country_code']").change(function(){
      $('body').css('cursor', 'wait');
      $.ajax({
        url: '<?php echo document::ilink('ajax/zones.json'); ?>?country_code=' + $(this).val(),
        type: 'get',
        cache: true,
        async: true,
        dataType: 'json',
        error: function(jqXHR, textStatus, errorThrown) {
          if (console) console.warn(errorThrown.message);
        },
        success: function(data) {
          $("select[name='zone_code']").html('');
          if ($("select[name='zone_code']").attr('disabled')) $("select[name='zone_code']").removeAttr('disabled');
          if (data) {
            $.each(data, function(i, zone) {
              $("select[name='zone_code']").append('<option value="'+ zone.code +'">'+ zone.name +'</option>');
            });
          } else {
            $("select[name='zone_code']").attr('disabled', 'disabled');
          }
        },
        complete: function() {
          $('body').css('cursor', 'auto');
        }
      });
    });
  </script>
</div>