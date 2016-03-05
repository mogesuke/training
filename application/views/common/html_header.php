<!DOCTYPE html>
<html>
<head>
	<title>Training Portal</title>
	<script src="/static_contents/js/jquery-1.12.0.min.js"></script>

	<!-- Bootstrap -->
	<script src="/static_contents/bootstrap/js/bootstrap.min.js"></script>
  <link href="/static_contents/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="/static_contents/css/jumbotron.css" rel="stylesheet">

  <!-- css -->
  <link href="/static_contents/css/style.css" rel="stylesheet">

</head>
<body>
<div class="container">

<?php if ($this->session->userdata(SESSION_NAME)) { ?>
	ようこそ <?php echo $this->session->userdata(SESSION_NAME); ?> さん <a href="/top/logout">[logout]</a>
<?php } ?>

  <div class="header clearfix">
    <nav>
      <ul class="nav nav-pills pull-right">
        <li role="presentation" class="active"><a href="#">Home</a></li>
        <li role="presentation"><a href="#">About</a></li>
        <li role="presentation"><a href="#">Contact</a></li>
      </ul>
    </nav>
    <a href="<?php echo base_url(); ?>"><h3 class="text-muted">Training Portal</h3></a>
  </div>
  <?php
    echo $this->session->flashdata('error');
  ?>