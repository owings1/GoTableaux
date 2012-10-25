<h1><?= $this->Html->link( 'GoTableaux', '/' ) ?></h1>
<ul id="nav">
	<li class="<?= $this->params['controller'] == 'logics' && $this->params['action'] == 'evaluate' ? 'selected' : '' ?>">
		<?= $this->Html->link( 'Evaluate', array( 'controller' => 'logics', 'action' => 'evaluate' )) ?>
	</li>
	<li class="<?= $this->params['controller'] == 'logics' && in_array( $this->params['action'], array( 'index', 'view' )) ? 'selected' : '' ?>">
		<?= $this->Html->link( 'Logics', array( 'controller' => 'logics', 'action' => 'index' )) ?>
	</li>
</ul>
<span style="float:right;">
	<?= $this->Html->link( 'GitHub Project Page', 'https://github.com/owings1/GoTableaux' ) ?>
</span>
<br>