<?php
$this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'general-settings-grid',
	'type'            => 'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'enableSorting' => false,
	'columns'=>array(
		'order_id',
		array('name' => 'movie.title','value' => '$data->movie->title', 'header' => 'Title'),
		array('name' => 'reting', 'header' => 'Rating'),
		array('name' => 'num_votes', 'header' => 'Votes')

	)));