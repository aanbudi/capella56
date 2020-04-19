<?php
class SalesController extends Controller {
	public $menuname = 'sales';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search()
	{
		header('Content-Type: application/json');
		$plant = isset ($_POST['plant']) ? $_POST['plant'] : '';
		$sales = isset ($_POST['sales']) ? $_POST['sales'] : '';
		$plantid = isset ($_POST['plantid']) ? $_POST['plantid'] : '';
		$plant = isset ($_GET['q']) ? $_GET['q'] : $plant;
		$sales = isset ($_GET['q']) ? $_GET['q'] : $sales;
		$plantid = isset ($_GET['plantid']) ? $_GET['plantid'] : $plantid;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'salesid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('count(1) as total')	
				->from('sales t')
				->leftjoin('plant p','p.plantid = t.plantid')
				->leftjoin('employee q','q.employeeid = t.employeeid')
				->where("((coalesce(p.plantcode,'') like :plantcode) 
				or (coalesce(q.fullname,'') like :fullname)) ".
				(($plantid != '')?" and p.plantid = ".$plantid:''),
					array(':plantcode'=>'%'.$plant.'%',':fullname'=>'%'.$sales.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('sales t')
				->leftjoin('plant p','p.plantid = t.plantid')
				->leftjoin('employee q','q.employeeid = t.employeeid')
				->where("((coalesce(p.plantcode,'') like :plantcode) 
				and (coalesce(q.fullname,'') like :fullname))",
					array(':plantcode'=>'%'.$plant.'%',':fullname'=>'%'.$sales.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('t.*,q.oldnik,q.fullname,(null) as plantcode')	
				->from('sales t')
				->leftjoin('plant p','p.plantid = t.plantid')
				->leftjoin('employee q','q.employeeid = t.employeeid')
				->where("((coalesce(p.plantcode,'') like :plantcode) 
				or (coalesce(q.fullname,'') like :fullname))".
				(($plantid != '')?" and p.plantid = ".$plantid:''),
					array(':plantcode'=>'%'.$plant.'%',':fullname'=>'%'.$sales.'%'))
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,q.oldnik,q.fullname,p.plantcode')	
				->from('sales t')
				->leftjoin('plant p','p.plantid = t.plantid')
				->leftjoin('employee q','q.employeeid = t.employeeid')
				->where("((coalesce(p.plantcode,'') like :plantcode) 
				and (coalesce(q.fullname,'') like :fullname)
				)",
					array(':plantcode'=>'%'.$plant.'%',':fullname'=>'%'.$sales.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'salesid'=>$data['salesid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'employeeid'=>$data['employeeid'],
				'oldnik'=>$data['oldnik'],
				'fullname'=>$data['fullname'],
				'limitsample'=>$data['limitsample'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call InsertSales(:vplantid,:vemployeeid,:vlimitsample,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call UpdateSales(:vid,:vplantid,:vemployeeid,:vlimitsample,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vplantid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vemployeeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vlimitsample',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-sales"]["name"]);
		if (move_uploaded_file($_FILES["file-sales"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$plantcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
					$oldnik = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$employeeid = Yii::app()->db->createCommand("select employeeid from employee where oldnik = '".$oldnik."'")->queryScalar();
					$limitsample = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$plantid,$employeeid,$limitsample));
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true,$e->getMessage());
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['salesid'])?$_POST['salesid']:''),
				$_POST['plantid'],$_POST['employeeid'],$_POST['limitsample']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgesales(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select salesid,employeeid,b.fullname as sales, plantid,c.plantcode,limitsample`
						from sales a 
						left join employee b on b.employeeid = a.employeeid 
						left join plant c on b.plantid = a.plantid 
						";
		$salesid = filter_input(INPUT_GET,'salesid');
		$sales = filter_input(INPUT_GET,'sales');
		$plantcode = filter_input(INPUT_GET,'plantcode');
		$sql .= " where coalesce(a.salesid,'') like '%".$salesid."%' 
			and coalesce(b.fullname,'') like '%".$sales."%'
			and coalesce(c.plantcode,'') like '%".$plantcode."%'
			";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.salesid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by plantcode asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('sales');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('salesid'),
																	GetCatalog('sales'),
																	GetCatalog('plantcode'),
																	GetCatalog('limitsample'));
		$this->pdf->setwidths(array(15,55,100,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['salesid'],$row1['sales'],$row1['plantcode'],$row1['limitsample']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='sales';
		parent::actionDownxls();
		$sql = "select salesid,employeeid,b.fullname as sales, plantid,c.plantcode,limitsample`
						from sales a 
						left join employee b on b.employeeid = a.employeeid 
						left join plant c on b.plantid = a.plantid 
						";
		$salesid = filter_input(INPUT_GET,'salesid');
		$sales = filter_input(INPUT_GET,'sales');
		$plantcode = filter_input(INPUT_GET,'plantcode');
		$sql .= " where coalesce(a.salesid,'') like '%".$salesid."%' 
			and coalesce(b.fullname,'') like '%".$sales."%'
			and coalesce(c.plantcode,'') like '%".$plantcode."%'
			";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.salesid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by plantcode asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('salesid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('plantcode'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('sales'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('limitsample'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['salesid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['sales'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['limitsample']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);	
	}
}