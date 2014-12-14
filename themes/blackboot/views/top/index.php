<?php
$this->breadcrumbs=array(
	'Top'=>array('index')
);


?>
<script type="text/javascript">

	var max_dat = '<?php echo $max_dat;?>';
	var min_dat = '<?php echo $min_dat;?>';


</script>
<h1>IMDB TOP 10</h1>
<?php
/*$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',array(
    'id' => 'inlineForm',
    'type' => 'inline',
    'htmlOptions' => array('class' => 'well'),
));
 echo $form->datepickerRow($model, 'rating_date',array('hint'=>'',
                                            'prepend'=>'<i class="icon-calendar"></i>',
                                            'options'=>array('format' => 'yyyy-mm-dd' , 'weekStart'=> 1)
));
$this->widget('bootstrap.widgets.TbButton',array(
                'buttonType' => 'Submit',
                'label' => 'Submit',
                 'type' => 'primary',
                 'url' => 'Link'  
 )); 

  $this->endWidget();

$this->widget(
    'bootstrap.widgets.TbDatePicker',
    array(
        'name' => 'from_date',
        'value'=> isset(Yii::app()->request->cookies['from_date']) ? Yii::app()->request->cookies['from_date']->value : '',
        'options' => array(
            'language' => 'en',
            'format' => 'yyyy-mm-dd',
        ),
        'htmlOptions'=>array('onchange' => "js: alert('sfgsfg');return true;"),
        
        
    )
);*/
?>
<div class='well'>

<div class="input-append date">
    <input id = 'datepicker' name = 'from_date' value="<?php $value1 = isset(Yii::app()->request->cookies['from_date']) ? Yii::app()->request->cookies['from_date']->value : '';echo $value1; ?>" contenteditable="false"
    <span class="add-on"><i class="icon-th"></i></span>
</div>
<script>
	$(function () {
		$('#datepicker').datepicker({
			dateFormat: 'yy-mm-dd',
		}).change(function () {
				var val1=$('#datepicker').val();
				var val2 = validatedate(val1,max_dat,min_dat);

                if(val2===false)
                {
	                $('#datepicker').val(max_dat);
	                val1= max_dat;
                }

				$.ajax({
					url     : '<?php echo Yii::app() -> createUrl('top/index'); ?>',
					type    : 'POST',
					data    : {from_date: val1},
					cache   : false,
					dataType:'html',
					success : function(data) {
						jQuery('#toptab').html(data)
					}
				});
				return false;
			})

	});
</script>
<div id="toptab">
	<?php $this->renderPartial('_top',array('model'=>$model));?>
</div>

