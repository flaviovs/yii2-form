Composite models (a.k.a forms) for Yii2
=======================================

The use case
------------

You need form input for separate model objects in your application.


Installation
------------

```sh
$ composer require flaviovs/yii2-form
```


How to
------

1. Create your form model. Add composing models as normal attributes:

	```php
	class MyForm extends fv\yii\form\Model
	{
		/** @var \app\models\Post */
		public $post;

		/** @var \app\models\Comment */
		public $comment;
	}
	```


2. Add the `modelAttributes()` function, which indicates which form
   attributes contain models to be loaded on submission:

	```php
	protected function modelAttributes()
	{
		return ['post', 'comment'];
	}
	```


3. Add input controls to your views as you normally would. Just
   remember to reference model attributes of the form, instead of the
   form model itself.

	```php
	/** @var \fv\yii\form\Model $model */

	$form = ActiveForm::begin();

	echo $form->field($model->post, 'title');
	echo $form->field($model->comment, 'body');

	ActiveForm::End();
	```


4. Process the form as you normally do:

	```php
	$model = new MyForm([
		'post' => new Post(),
		'comment' => new Comment(),
	]);
	if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
		$model->post->save();
		$model->comment->save();
	}
	```


Support
-------
https://github.com/flaviovs/yii2-form
