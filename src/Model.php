<?php

namespace fv\yii\form;

abstract class Model extends \yii\base\Model
{
    abstract protected function modelAttributes();

    public function load($data, $form = null)
    {
        $active_models = $this->getActiveModels();
        if (
            array_diff($active_models, $this->activeAttributes())
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


    public function afterValidate()
    {
        foreach ($this->getActiveModels() as $attr) {
            if (!$this->$attr->validate()) {
                $this->addError(
                    $attr,
                    \Yii::t('app', 'Model does not validate')
                );
            }
        }

        parent::afterValidate();
    }
}
