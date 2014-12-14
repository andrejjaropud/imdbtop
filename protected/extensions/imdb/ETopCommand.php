<?php
/**
 * Created by JetBrains PhpStorm.
 * User: A.Iaropud
 * Date: 10.12.14
 * Time: 16:56
 * Console Command to get data from imdb.com
 */

class ETopCommand extends CConsoleCommand{

/**
 * Get data from page http://www.imdb.com/chart/top and insert data to DB
 */
	public function actionGettop()
	{
		$cur_date = Date('Y-m-d');
		$flag_ex = 0;

// Check existing top for current date

		if (($model=Top::model()->find('rating_date=:rating_date', array(':rating_date' => $cur_date)))===null) // if rating is not exist
		{
			$flag_ex =1;
		}


			$data = Yii::app()->imdbs->chart_top();


			if(isset($data[0]))
			{
				for($i = 0;$i < 20; $i++)
				{
					if (($model=Movies::model()->find('tconst=:tconst', array(':tconst' => $data[$i]->tconst)))===null)
					{
						$model = new Movies;
						$model->tconst = $data[$i]->tconst;
						$model->title = $data[$i]->title;
						$model->year = $data[$i]->year;
						$model->save();
						$movie_id = $model->id;
					}
					else
					{
						$movie_id = $model->id;
					}

					if($flag_ex > 0)
					{
						$rat = new Top;
						$rat->rating_date = $cur_date;
						$rat->order_id = $i+1;
					}
					else
					{
						$rat=Top::model()->findByAttributes(array('order_id'=>$i+1,'rating_date'=>$cur_date));
					}

					$rat->movie_id = $movie_id;
					$rat->reting = $data[$i]->rating;
					$rat->num_votes = $data[$i]->num_votes;
					$rat->updated = date("Y-m-d H:i:s");
					$rat->save();
				}
			}

	}

	public function actionGettophtml()
	{
		$cur_date = Date('Y-m-d');
		$flag_ex = 0;

// Check existing top for current date

		if (($model=Top::model()->find('rating_date=:rating_date', array(':rating_date' => $cur_date)))===null) // if rating is not exist
		{
			$flag_ex =1;
		}

		$data = Yii::app()->imdbh->getData();

		if(isset($data[0]))
		{
			for($i = 0;$i < 20; $i++)
			{
				if (($model=Movies::model()->find('tconst=:tconst', array(':tconst' => $data[$i]['tconst'])))===null)
				{
					$model = new Movies;
					$model->tconst = $data[$i]['tconst'];
					$model->title = $data[$i]['title'];
					$model->year = $data[$i]['year'];
					$model->save();
					$movie_id = $model->id;
				}
				else
				{
					$movie_id = $model->id;
				}

				if($flag_ex > 0)
				{
					$rat = new Top;
					$rat->rating_date = $cur_date;
					$rat->order_id = $i+1;
				}
				else
				{
					$rat=Top::model()->findByAttributes(array('order_id'=>$i+1,'rating_date'=>$cur_date));
				}

				$rat->movie_id = $movie_id;
				$rat->reting = $data[$i]['rating'];
				$rat->num_votes = $data[$i]['num_votes'];
				$rat->updated = date("Y-m-d H:i:s");
				$rat->save();
			}
		}

	}

	public function getHelp(){
		$out = "Help for ETopCommand\n\n";
		return $out.parent::getHelp();
	}
}