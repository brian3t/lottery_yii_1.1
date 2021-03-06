<?php

class WinningGroupController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/jumbotron';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view', 'get','batchUpdate'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'roles'=>array('*'),
			)
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new WinningGroup;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['WinningGroup'])) {
			$model->attributes=$_POST['WinningGroup'];
			if ($model->save()) {
				if (!empty($_GET['totoResultId'])){
					$this->redirect(array('totoResult/view','id' => $_GET['totoResultId']));
				}
				else{
					$this->redirect(array('view','id'=>$model->id));
				}
			}
		}

		$model->toto_result_id = app()->getRequest()->getQuery('totoResultId');
		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['WinningGroup'])) {
			$model->attributes=$_POST['WinningGroup'];
			if ($model->save()) {
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			}
		} else {
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('WinningGroup');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new WinningGroup('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['WinningGroup'])) {
			$model->attributes=$_GET['WinningGroup'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return WinningGroup the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=WinningGroup::model()->findByPk($id);
		if ($model===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param WinningGroup $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax']==='winning-group-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * Find list of results based on parameters
	 */
	public function actionGet()
	{
		$totoModel=TotoResult::model();
		$totoId=Yii::app()->getRequest()->getQuery('totoId');
		$totoDate=Yii::app()->getRequest()->getQuery('totoDate');
		$p=array();

		if(!empty($id))
		{
			$p['id']=$totoId;
		}
		if(!empty($date))
		{
			$p['date']=$totoDate;
		}
		$data=$totoModel->findByAttributes($p);

			$winningGroups=$data->winningGroups;

			$wg=array_map(function ($arr)
			{
				return $arr->getAttributes();
			},$winningGroups);
			$dataArray=array_merge($data->getAttributes(),array('winning_groups'=>$wg));
		echo CJavaScript::jsonEncode(array('winning_groups'=>$wg));
		Yii::app()->end();
	}

	public function getItemsToUpdate() {
		// Create an empty list of records
		$items = array();

		// Iterate over each item from the submitted form
		if (isset($_POST['WinningGroup']) && is_array($_POST['Item'])) {
			foreach ($_POST['WinningGroup'] as $item) {
				// If item id is available, read the record from database
				if ( array_key_exists('id', $item) ){
					$items[] = WinningGroup::model()->findByPk($item['id']);
				}
				// Otherwise create a new record
				else {
					$items[] = new WinningGroup();
				}
			}
		}
		return $items;
	}

	public function actionBatchUpdate()
	{
		$item = new WinningGroup();
		$toto_result_id = app()->getRequest()->getQuery('totoResultId');
		$p = array();
		if (!empty($toto_result_id)){
			$p['toto_result_id'] = $toto_result_id;
		}
		$items=WinningGroup::model()->findAllByAttributes($p);


		if(isset($_POST['WinningGroup']))
		{

			$valid=true;
			foreach($items as $i=>$item)
			{
				if(isset($_POST['WinningGroup'][$i]))

					$item->attributes=$_POST['WinningGroup'][$i];
				$valid=$item->validate() && $valid;
				if($valid)$item->save();
			}
			if($valid){
				// all items are valid
				// redirect to the parent totoresult
				$this->redirect(array('totoResult/view','id'=>$items[0]->toto_result_id));
			}
			else{
				throw new CHttpException(500,'Error. Could not save winning groups in batch');
			}

		}

		// displays the view to collect tabular input
		$this->render('batchUpdate',array(
			'items'=>$items));

	}


}
