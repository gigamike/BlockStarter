<?php
namespace Project\Model;

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
use Project\Model\ProjectContributorEntity;

class ProjectContributorMapper
{
	protected $tableName = 'project_contributor';
	protected $dbAdapter;
	protected $sql;

	public function __construct(Adapter $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
	}

	public function fetch($paginated=false, $filter = array(), $order=array(), $limit = null)
	{
		$select = $this->sql->select();
		$where = new \Zend\Db\Sql\Where();

		if(isset($filter['id'])){
			$where->equalTo("id", $filter['id']);
		}

		if(isset($filter['project_id'])){
			$where->equalTo("project_id", $filter['project_id']);
		}

		if (!empty($where)) {
			$select->where($where);
		}

		if(count($order) > 0){
		    $select->order($order);
		}

		if(!is_null($limit)){
			$select->limit($limit);
		}

		// echo $select->getSqlString($this->dbAdapter->getPlatform());exit();

		if($paginated) {
		    $entityPrototype = new ProjectContributorEntity();
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

		    $entityPrototype = new ProjectContributorEntity();
		    $hydrator = new ClassMethods();
		    $resultset = new HydratingResultSet($hydrator, $entityPrototype);
		    $resultset->initialize($results);
		}

		return $resultset;
	}

	public function save(ProjectContributorEntity $projectContributor)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($projectContributor);

		if ($projectContributor->getId()) {
			// update action
			$action = $this->sql->update();
			$action->set($data);
			$action->where(array('id' => $projectContributor->getId()));
		} else {
			// insert action
			$action = $this->sql->insert();
			unset($data['id']);
			$action->values($data);
		}
		$statement = $this->sql->prepareStatementForSqlObject($action);
		$result = $statement->execute();

		if (!$projectContributor->getId()) {
			$projectContributor->setId($result->getGeneratedValue());
		}
		return $result;
	}

	public function getProjectContributor($id)
	{
		$select = $this->sql->select();
		$select->where(array('id' => $id));

		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute()->current();
		if (!$result) {
			return null;
		}

		$hydrator = new ClassMethods();
		$projectContributor = new ProjectContributorEntity();
		$hydrator->hydrate($result, $projectContributor);

		return $projectContributor;
	}

	public function delete($id)
	{
    $delete = $this->sql->delete();
    $delete->where(array('id' => $id));

    $statement = $this->sql->prepareStatementForSqlObject($delete);
    return $statement->execute();
	}

	public function getCountProjectContributor($filter = array())
	{
    $select = $this->sql->select();
    $select->columns(array(
    	'count_id' => new \Zend\Db\Sql\Expression("COUNT(" . $this->tableName . ".id)"),
    ));

    $where = new \Zend\Db\Sql\Where();

    if(isset($filter['project_id']) && !empty($filter['project_id'])){
      $where->equalTo("project_id", $filter['project_id']);
    }

    if (!empty($where)) {
      $select->where($where);
    }

    // echo $select->getSqlString($this->dbAdapter->getPlatform()) . "<br />"; exit();

    $statement = $this->sql->prepareStatementForSqlObject($select);
    $result = $statement->execute()->current();
    if (!$result) {
      return null;
    }

    return $result;
	}

	public function getSumProjectContributorAmount($filter = array())
	{
    $select = $this->sql->select();
    $select->columns(array(
    	'sum_amount' => new \Zend\Db\Sql\Expression("SUM(" . $this->tableName . ".amount)"),
    ));

    $where = new \Zend\Db\Sql\Where();

    if(isset($filter['project_id']) && !empty($filter['project_id'])){
      $where->equalTo("project_id", $filter['project_id']);
    }

    if (!empty($where)) {
      $select->where($where);
    }

    // echo $select->getSqlString($this->dbAdapter->getPlatform()) . "<br />"; exit();

    $statement = $this->sql->prepareStatementForSqlObject($select);
    $result = $statement->execute()->current();
    if (!$result) {
      return null;
    }

    return $result;
	}
}
