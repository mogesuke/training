<?php if (is_null($english_category)) { ?>
	<h1>指定された問題はありません。</h1>
<?php } else { ?>
	<h1><?php echo $english_category->name; ?></h1>
<?php } ?>