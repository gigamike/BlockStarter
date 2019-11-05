<?php
namespace Member\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Member\Model\MedicalRecordEntity;

class MedicalRecordMapper
{
	protected $tableName = 'medical_record';
	protected $dbAdapter;
	protected $sql;

	public function __construct(Adapter $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
	}

	public function fetch($paginated=false, $filter = array(), $order=array())
	{
		$select = $this->sql->select();
		$where = new \Zend\Db\Sql\Where();

		if(isset($filter['id'])){
			$where->equalTo("id", $filter['id']);
		}

		if(isset($filter['id_not'])){
	    $where->notEqualTo("id", $filter['id_not']);
		}

		if(isset($filter['name_keyword'])){
			$where->addPredicate(
					new \Zend\Db\Sql\Predicate\Like("name", "%" . $filter['name_keyword'] . "%")
			);
		}

		if(isset($filter['tags_keyword'])){
			$where->addPredicate(
					new \Zend\Db\Sql\Predicate\Like("tags", "%" . $filter['tags_keyword'] . "%")
			);
		}

		if (!empty($where)) {
			$select->where($where);
		}

		if(count($order) > 0){
			$select->order($order);
		}

		// echo $select->getSqlString($this->dbAdapter->getPlatform());exit();

		if($paginated) {
	    $entityPrototype = new MedicalRecordEntity();
	    $hydrator = new ClassMethods();
	    $resultset = new HydratingResultSet($hydrator, $entityPrototype);

			$paginatorAdapter = new DbSelect(
				$select,
				$this->dbAdapter,
				$resultset
			);
			$paginator = new Paginator($paginatorAdapter);
			return $paginator;
		}else{
	    $statement = $this->sql->prepareStatementForSqlObject($select);
	    $results = $statement->execute();

	    $entityPrototype = new MedicalRecordEntity();
	    $hydrator = new ClassMethods();
	    $resultset = new HydratingResultSet($hydrator, $entityPrototype);
	    $resultset->initialize($results);
		}

		return $resultset;
	}

	public function save(MedicalRecordEntity $memberRecord)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($memberRecord);

		if ($memberRecord->getId()) {
			// update action
			$action = $this->sql->update();
			$action->set($data);
			$action->where(array('id' => $memberRecord->getId()));
		} else {
			// insert action
			$action = $this->sql->insert();
			unset($data['id']);
			$action->values($data);
		}
		$statement = $this->sql->prepareStatementForSqlObject($action);
		$result = $statement->execute();

		if (!$memberRecord->getId()) {
			$memberRecord->setId($result->getGeneratedValue());
		}
		return $result;
	}

	public function getMedicalRecord($id)
	{
		$select = $this->sql->select();
		$select->where(array('id' => $id));

		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute()->current();
		if (!$result) {
			return null;
		}

		$hydrator = new ClassMethods();
		$memberRecord = new MedicalRecordEntity();
		$hydrator->hydrate($result, $memberRecord);

		return $memberRecord;
	}

	public function delete($id)
	{
    $delete = $this->sql->delete();
    $delete->where(array('id' => $id));

    $statement = $this->sql->prepareStatementForSqlObject($delete);
    return $statement->execute();
	}
}
