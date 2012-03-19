<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?= $this->Html->charset() ?>
	<title>
		<?= $title_for_layout ?>
	</title>
	
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css( array( '960', 'style' ));
		echo $this->element( 'define_webroot' );
		echo $this->fetch( 'meta' );
		echo $this->fetch( 'css' );
		echo $this->Html->script( 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js' );
		echo $this->fetch( 'script' );
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<?= $this->element( 'menu' ) ?>
		</div>
		<div id="content">

			<?= $this->Session->flash() ?>

			<?= $this->fetch( 'content' ) ?>
		</div>
		<div id="footer">
			Copyright &copy; 2011-<?= date( 'Y' ) ?> Douglas Owings. Released under the <?= $this->Html->link( 'BSD license.', 'http://www.opensource.org/licenses/bsd-license.php' ) ?>
		</div>
	</div>
</body>
</html>
