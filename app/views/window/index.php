<div id='content'>
<?php foreach ($Window as $window):?>
	<div class='artical-title'>
		<h3><?php echo $window['title'];?></h3>
	</div>
	<div class='artical-content'>
		<p><?php echo $window['content'];?></p>
	</div>
<?php endforeach;?>
	<div>
		<!--<img src='<?php echo $html->imgPath . 'abc.jpg'?>'>-->
	</div>
</div>