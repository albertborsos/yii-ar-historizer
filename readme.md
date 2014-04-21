Yii Model Historizer
====================

Simple extension to log your models change history. Independent from your models table structure, because it stores the attributes in one field in JSON format.

Installation steps
==================

1.) Via composer:

```
    "require":{
        "albertborsos/historizer": "dev-master",
    }
```

2.) Create the table for the histories by the initialize schema in `datas` folder

3.) Then, add this line to your protected/config/main.php

```
    'import' => array(
        'application.vendor.albertborsos.historizer',
    ),
```

How To Use
==========
In your model's class modify the `beforeSave()` and `beforeDelete()` methods
If the new attributes are different from the old one, it saves the old model's attributes automatically.
If save happened the historize() methods return value is true, else false.

```
public function beforeSave(){
        if (parent::beforeSave()){
            if (!$this->isNewRecord){
               if (AHistorizer::historize($this)){
                   // if attributes are modified, update fields
                   $this->date_update = date('Y-m-d H:i:s');
                   $this->user_update = Yii::app()->user->id;
               }
            }else{
               $this->user_create = Yii::app()->user->id;
               $this->date_create = date('Y-m-d H:i:s');
            }
            return true;
        }else{
            return false;
        }
    }

    public function beforeDelete() {
        if (parent::beforeDelete()){
            AHistorizer::historize($this);
            return true;
        }else{
            return false;
        }
    }
```