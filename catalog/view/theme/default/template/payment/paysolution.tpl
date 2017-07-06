<form action="<?php echo $action; ?>" method="post" id="payment">

<?php foreach ($paysolution as $key=>$value) { ?>
	<?php if(gettype($value)=='string'){ ?>
		<input type="hidden" name="<?=$key;?>" value="<?=$value;?>"/>
	<?php }else{ ?>
		<input type="hidden" name="<?=$key;?>" value="<?=print_r($value);?>"/>
	<?php } ?>
<?php } ?>

<div class="buttons">
  <div class="pull-right">
    <input type="button" onclick="$('#payment').submit();" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
</form>
