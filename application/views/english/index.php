<h1>English Training</h1>
<div class="row marketing">
  <div class="col-lg-6">
	<?php foreach ($categorys as $category) { ?>
		<h4>
			<a href="<?php echo base_url() .$this->router->fetch_class() ."/" .$category->id; ?>" >
				<?php echo $category->name; ?>
			</a>
		</h4>
		<p><?php echo $category->description ?></p>
	<?php } ?>
  </div>
</div>