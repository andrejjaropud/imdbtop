<?php

/**
 * This is the model class for table "ratings".
 *
 * The followings are the available columns in table 'ratings':
 * @property integer $id
 * @property string $rating_date
 * @property integer $order_id
 * @property integer $movie_id
 * @property string $reting
 * @property integer $num_votes
 *
 * The followings are the available model relations:
 * @property Movies $movie
 */
class Top extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ratings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rating_date, order_id, movie_id, reting, num_votes', 'required'),
			array('order_id, movie_id, num_votes', 'numerical', 'integerOnly'=>true),
			array('reting', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rating_date, order_id, movie_id, reting, num_votes', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'movie' => array(self::BELONGS_TO, 'Movies', 'movie_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'rating_date' => 'Rating Date',
			'order_id' => 'Order',
			'movie_id' => 'Movie',
			'reting' => 'Reting',
			'num_votes' => 'Num Votes',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$dependency = new CDbCacheDependency('SELECT MAX(UNIX_TIMESTAMP(updated)) FROM ratings where rating_date=:rating_date');
		$dependency->params = array(':rating_date'=>$this->rating_date);
		$duration = 3600;
		$criteria=new CDbCriteria;

        $criteria->with = array( 'movie' );
		$criteria->compare('id',$this->id);
		$criteria->compare('rating_date',$this->rating_date,true);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('movie_id',$this->movie_id);
		$criteria->compare('reting',$this->reting,true);
		$criteria->compare('num_votes',$this->num_votes);
//		$criteria->order = ' order_id ASC ';
        $criteria->limit = 10;


        
		return new CActiveDataProvider($this->cache($duration, $dependency, 2), array(
			'criteria'=>$criteria,
			'pagination'=>false
		));
	}

	/**
	 * Get max date of ratings in DB 
	 */
	public function getMaxratingdate()
	{
		$data  = Yii::app()->db->createCommand()
            ->select('max(rating_date)')
            ->from(' ratings')
            ->queryScalar();

        return $data;
	}

	/**
	 * Get mmin date of ratings in DB
	 */
	public function getMinratingdate()
	{
		$data  = Yii::app()->db->createCommand()
			->select('min(rating_date)')
			->from(' ratings')
			->queryScalar();

		return $data;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Top the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
