  <div class="jumbotron">
    <h1>No Training<br>No Life</h1>
    <p class="lead">We will support your training.<br>Let's Training!</p>
    <?php if (!$this->isLogin()) { ?>
      <p><a class="btn btn-lg btn-success" href="<?php echo $oauth_url?>" role="button">Google Login</a></p>
    <?php } else { ?>
    	<ul>
	    	<?php foreach ($contents as $content) { ?>
          <?php print_r($content); ?>
	    		<li>
	    			<a href="<?php echo base_url() .$content->path;?>">
			    		<span class="<?php echo $content->class; ?>" data-toggle="popover" ></span>
			    		<?php echo $content->name; ?>
		    		</a>
            <a href="">manage tool</a>
	    		</li>
	    	<?php } ?>
   		</ul>
    <?php } ?>

  </div>
