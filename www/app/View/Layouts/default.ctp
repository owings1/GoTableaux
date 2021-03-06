<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?= $this->Html->charset() ?>
	<title>
		<?= $title_for_layout ?>
	</title>
	
	<?php
		echo $this->Html->css( array( '960', 'style', 'jquery-ui' ));
		echo $this->element( 'define_webroot' );
		echo $this->fetch( 'meta' );
		echo $this->fetch( 'css' );
		echo $this->Html->script( 'jquery.min.js' );
		echo $this->Html->script( 'jquery-ui.min' );
		echo $this->Html->script( 'processing-1.3.6.min.js' );
		echo $this->Html->script( 'processingTableauWriter' );
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
			Copyright &copy; <?= date( 'Y' ) ?> Douglas Owings. Released under the <?= $this->Html->link( 'GNU AGPL.', 'http://www.gnu.org/licenses/agpl-3.0.html' ) ?>
		</div>
	</div>
</body>
</html>