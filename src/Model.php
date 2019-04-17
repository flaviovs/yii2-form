<?php

namespace fv\yii\form;

abstract class Model extends \yii\base\Model
{
    abstract protected function modelAttributes();

    public function load($data, $form = null)
    {
        $active_models = $this->getActiveModels();
        if (
            $active_models != $this->activeAttributes()
            && !parent::load($data, $form)
        ) {
            return false;
        }

        foreach ($active_models as $attr) {
            if (!$this->$attr->load($data)) {
                return false;
            }
        }

        return true;
    }


    protected function getActiveModels()
    {
        $models = $this->modelAttributes();
        $active = $this->activeAttributes();
        return array_intersect($models, $active);
    }


    public function beforeValidate()
    {
        $fail = 0;

        if (!parent::beforeValidate()) {
            $fail++;
        }

        foreach ($this->getActiveModels() as $attr) {
            if (!$this->$attr->validate()) {
                $fail++;
            }
        }

        return $fail === 0;
    }
}
