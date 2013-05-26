<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $html->head_title;?></title>
	<?php echo $html->includeStl() . $html->includeJs();?>
</head>

<body>
<?php if ($html->page_nav):?>
	<nav id='nav'>
		<?php echo $html->page_nav;?>
	</nav>
<?php endif;?>
	<section id='wrapper'>
	<?php if ($html->page_title):?>
		<div id="page-title">
			<h1><?php echo $html->page_title;?></h1>
		</div>
	<?php endif;?>

	<?php if ($html->page_subtitle):?>
		<div id="page-subtitle">
			<h2><?php echo $html->page_subtitle;?></h2>
		</div>
	<?php endif;?>
	<div class="rens-message"></div>