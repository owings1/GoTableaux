<h2>Logics</h2>
<table class="dataTable">
	<thead>
		<tr>
			<th>Name</th>
			<th>Title</th>
		</tr>
	</thead>
	<tbody>
		<? foreach( $logics as $logic ) : ?>
			<tr>
				<td><?= $this->Html->link( $this->Inflect->human( $logic->getName() ), array( 'controller' => 'logics', 'action' => 'view', $logic->getName() )) ?></td>
				<td><?= $logic->title ?></td>
			</tr>
		<? endforeach ?>
	</tbody>
</table>

