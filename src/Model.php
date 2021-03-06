<?php

namespace fv\yii\form;

abstract class Model extends \yii\base\Model
{
    abstract protected function modelAttributes();

    public function load($data, $form = null)
    {
        $active_models = $this->getActiveModels();
        if (
            array_diff($this->activeAttributes(), $active_models)
            && !parent::load($data, $form)
            && !$active_models
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
        return array_intersect(
            $this->modelAttributes(),
            $this->activeAttributes()
        );
    }


    public function afterValidate()
    {
        foreach ($this->getActiveModels() as $attr) {
            $model = $this->$attr;

            if (!$model) {
                \Yii::trace("Empty attribute \"$attr\"", __METHOD__);
                continue;
            }

            if (!($model instanceof \yii\base\Model)) {
                \Yii::trace("Value of \"$attr\" is not a model", __METHOD__);
                continue;
            }

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
