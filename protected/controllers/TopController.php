<?php

class TopController extends Controller
{
	public function actionIndex()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$model = new Top('search');
			$model->unsetAttributes(); // clear any default values
			$dat_m = $model->maxratingdate;

			if(isset($_POST['from_date']) && !empty($_POST['from_date']))
			{
				$curr_dat = $_POST['from_date'];
			}
			else
			{
				$curr_dat = $dat_m;
			}

			$model->rating_date = $curr_dat ;
			unset(Yii::app()->request->cookies['from_date']);  // first unset cookie for dates
			$this->setCookie('from_date',$curr_dat);
			$this->renderPartial('_top',array('model'=>$model));
		} 
		else 
		{
			$cs = Yii::app()->clientScript;
            $cs->registerScriptFile(Yii::app()->baseUrl . '/js/buttonGroup.js', CClientScript::POS_END);
            
			$model = new Top('search');
			$model->unsetAttributes(); // clear any default values
			$dat_m = $model->maxratingdate;
			$dat_min = $model->minratingdate;
			$model->rating_date = $dat_m ;
			unset(Yii::app()->request->cookies['from_date']);  // first unset cookie for dates
            $this->setCookie('from_date',$dat_m);
			$this->render('index', array(
				'model' => $model,
				'max_dat' => $dat_m,
				'min_dat' => $dat_min,
			));
		}
    }
    
    /**
     * set cookie value
     *
     * @param $name
     * @param $val
     */
    private function setCookie($name,$val)
    {
        $cookie = new CHttpCookie($name, $val);  // define cookie for from_date
        $cookie->expire = time() + (60*60);
        Yii::app()->request->cookies[$name] = $cookie;
    }
}
