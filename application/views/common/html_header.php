<!DOCTYPE html>
<html>
<head>
	<title>test</title>
	<script src="/static_contents/js/jquery-1.12.0.min.js"></script>

	<!-- Bootstrap -->
	<script src="/static_contents/bootstrap/js/bootstrap.min.js"></script>
    <link href="/static_contents/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="/static_contents/css/jumbotron.css" rel="stylesheet">
</head>
<body>
<div class="container">

<?php if ($this->session->userdata("name")) { ?>
	ようこそ <?php echo $this->session->userdata("name"); ?> さん <a href="/top/logout">[logout]</a>
<?php } ?>

  <div class="header clearfix">
    <nav>
      <ul class="nav nav-pills pull-right">
        <li role="presentation" class="active"><a href="#">Home</a></li>
        <li role="presentation"><a href="#">About</a></li>
        <li role="presentation"><a href="#">Contact</a></li>
      </ul>
    </nav>
    <h3 class="text-muted">my portal</h3>
  </div>
  <?php
    echo $this->session->flashdata('error');
  ?>