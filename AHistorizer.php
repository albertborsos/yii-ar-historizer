<?php

/**
 * This is the model class for table "ext_tbl_ahistorizer_histories".
 *
 * The followings are the available columns in table 'a_model_histories':
 * @property string $id
 * @property string $model_class
 * @property integer $model_id
 * @property string $model_attributes
 * @property string $date_create
 * @property string $user_create
 * @property integer $status
 */
class AHistorizer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ext_tbl_ahistorizer_histories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model_class, model_id, model_attributes, date_create, status', 'required'),
			array('model_id', 'numerical', 'integerOnly'=>true),
			array('model_class', 'length', 'max'=>256),
			array('user_create', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, model_class, model_id, model_attributes, date_create, user_create, status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'model_class' => 'Model Class',
			'model_id' => 'Model',
			'model_attributes' => 'Model Attributes',
			'date_create' => 'Date Create',
			'user_create' => 'User Create',
			'status' => 'Status',
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
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('model_class',$this->model_class,true);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('model_attributes',$this->model_attributes,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('user_create',$this->user_create,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModelHistories the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function throw_exception($title = null){
        if ($title != null) $title = '<h4>'.$title.'</h4>';
        throw new Exception(CHtml::errorSummary($this, $title, '', array('class'=>'')));
    }

    public function beforeValidate(){
        if (parent::beforeValidate()){
			$this->date_create = date('Y-m-d H:i:s');
			$this->user_create = Yii::app()->user->id;
			$this->status      = 'a';
            return true;
        }else{
            return false;
        }
    }

    public function beforeSave(){
        if (parent::beforeSave()){
            return true;
        }else{
            return false;
        }
    }
    public function afterSave() {
        parent::afterSave();
        // ide jön a kódod
    }
    public function beforeDelete() {
        if (parent::beforeDelete()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Elmenti a model attribútumait, ha módosult
     * @param $model
     * @return bool
     */
    public static function historize($model){

		$class = get_class($model);

		$old_model = $class::model()->findByPk($model->getPrimaryKey());

		//összehasonlítom a 2 model attribútumait, ha különbözik, akkor menteni kell
		if (!self::objects_attributes_are_same($model, $old_model)){
			// ha különbözik, akkor módosult
			$history = new AHistorizer();
			$history->model_class      = $class;
			$history->model_id         = $model->primaryKey;
			$history->model_attributes = CJSON::encode($old_model->attributes);

			if (!$history->save()){
				$history->throw_exception('Archiválás nem sikerült!');
			}
			return true;
		}else{
			// ha nem különbözik, akkor false, mert nem mentette
			return false;
		}
	}

    /**
     * historizálásnál hasonlítja össze az objektumokat
     *
     * @param $obj_1
     * @param $obj_2
     * @return bool
     */
    public static function objects_attributes_are_same($obj_1, $obj_2){
        $obj_attr_1 = $obj_1->attributes;
        $obj_attr_2 = $obj_2->attributes;
        foreach ($obj_attr_1 as $attribute_id => $value){
            if ($obj_attr_1[$attribute_id] !== $obj_attr_2[$attribute_id]){
                //ha nem egyezik, akkor return false;
                return false;
            }
        }
        return true;
    }
}
