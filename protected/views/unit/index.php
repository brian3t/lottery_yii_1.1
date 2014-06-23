<?php
/* @var $this UnitController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs=array(
	'Units',
);
$b = Yii::app()->request->baseUrl;

$this->menu=array(
	array('label'=>'Create Unit','url'=>array('create')),
	array('label'=>'Manage Unit','url'=>array('admin')),
);
$thisClass = get_class($this);
$modelName = substr($thisClass,0, strlen($thisClass) - strlen("Controller"));
?>

<h1>Units</h1>
<div class="col-md-9">

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
</div>
<div class="col-md-3">
	<div class="widget">
		<h3>
			Operations
		</h3>
		<ul class="list-group">
			<li class="list-group-item"><a href="<?=$b?>/<?=$modelName?>">List <?=$modelName?></a></li>
			<li class="list-group-item"><a href="<?=$b?>/<?=$modelName?>/create">Create <?=$modelName?></a></li>
			<li class="list-group-item"><a href="<?=$b?>/<?=$modelName?>/admin">Manage <?=$modelName?></a></li>
		</ul>
	</div>
</div>