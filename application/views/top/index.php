  <div class="jumbotron">
    <h1>No Training<br>No Life</h1>
    <p class="lead">We will support your training.<br>Let's Training!</p>
    <?php if (!$this->isLogin()) { ?>
      <p><a class="btn btn-lg btn-success" href="<?php echo $oauth_url?>" role="button">Google Login</a></p>
    <?php } else { ?>
    	<ul>
	    	<?php foreach ($contents as $row) { ?>
	    		<li>
	    			<a href="<?php echo base_url() .$row->path;?>">
			    		<span class="<?php echo $row->class; ?>" data-toggle="popover" ></span>
			    		<?php echo $row->name; ?>
		    		</a>
	    		</li>
	    	<?php } ?>
   		</ul>
    <?php } ?>

  </div>
