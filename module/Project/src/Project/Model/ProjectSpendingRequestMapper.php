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
use Project\Model\ProjectSpendingRequestEntity;

class ProjectSpendingRequestMapper
{
	protected $tableName = 'project_spending_request';
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

		if(isset($filter['supplier_id'])){
			$where->equalTo("supplier_id", $filter['supplier_id']);
		}

		if(isset($filter['description'])){
		    $where->equalTo("description", $filter['description']);
		}

		if(isset($filter['description_keyword'])){
			$where->addPredicate(
					new \Zend\Db\Sql\Predicate\Like("description", "%" . $filter['description_keyword'] . "%")
			);
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
		    $entityPrototype = new ProjectSpendingRequestEntity();
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

		    $entityPrototype = new ProjectSpendingRequestEntity();
		    $hydrator = new ClassMethods();
		    $resultset = new HydratingResultSet($hydrator, $entityPrototype);
		    $resultset->initialize($results);
		}

		return $resultset;
	}

	public function save(ProjectSpendingRequestEntity $projectSpendingRequest)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($projectSpendingRequest);

		if ($projectSpendingRequest->getId()) {
			// update action
			$action = $this->sql->update();
			$action->set($data);
			$action->where(array('id' => $projectSpendingRequest->getId()));
		} else {
			// insert action
			$action = $this->sql->insert();
			unset($data['id']);
			$action->values($data);
		}
		$statement = $this->sql->prepareStatementForSqlObject($action);
		$result = $statement->execute();

		if (!$projectSpendingRequest->getId()) {
			$projectSpendingRequest->setId($result->getGeneratedValue());
		}
		return $result;
	}

	public function getProjectSpendingRequest($id)
	{
		$select = $this->sql->select();
		$select->where(array('id' => $id));

		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute()->current();
		if (!$result) {
			return null;
		}

		$hydrator = new ClassMethods();
		$projectSpendingRequest = new ProjectSpendingRequestEntity();
		$hydrator->hydrate($result, $projectSpendingRequest);

		return $projectSpendingRequest;
	}

	public function delete($id)
	{
    $delete = $this->sql->delete();
    $delete->where(array('id' => $id));

    $statement = $this->sql->prepareStatementForSqlObject($delete);
    return $statement->execute();
	}

	public function getProjectSpendingRequests($paginated=false, $filter = array(), $order = array())
	{
    $select = $this->sql->select();
    $select->columns(array(
	    'id',
	    'project_id',
	    'description',
	    'amount',
	    'supplier_id',
	    'created_datetime',
			'is_finalized',
			'vote_yes' => new \Zend\Db\Sql\Expression("(SELECT COUNT(id)
	                                                       FROM project_spending_request_vote
	                                                       WHERE project_spending_request_vote.project_spending_request_id = " . $this->tableName . ".id AND is_approved = 'Y')"),

			'vote_no' => new \Zend\Db\Sql\Expression("(SELECT COUNT(id)
		                                                       FROM project_spending_request_vote
		                                                       WHERE project_spending_request_vote.project_spending_request_id = " . $this->tableName . ".id AND is_approved = 'N')"),
		));
    $select->join(
      'supplier',
      $this->tableName . ".supplier_id = supplier.id",
      array(
				'name',
				'eos_public_address',
      ),
      $select::JOIN_INNER
    );

    $where = new \Zend\Db\Sql\Where();

		if(isset($filter['project_id'])){
			$where->equalTo($this->tableName . ".project_id", $filter['project_id']);
		}

    if (!empty($where)) {
      $select->where($where);
    }

    if(count($order) > 0){
      $select->order($order);
    }

    // echo $select->getSqlString($this->dbAdapter->getPlatform()) . "<br>";

    if($paginated) {
      $paginatorAdapter = new DbSelect(
        $select,
        $this->dbAdapter
      );
      $paginator = new Paginator($paginatorAdapter);
      return $paginator;
    }else{
      $statement = $this->sql->prepareStatementForSqlObject($select);
      $result = $statement->execute();
      if ($result instanceof ResultInterface && $result->isQueryResult()) {
        $resultSet = new ResultSet;
        $resultSet->initialize($result);
      }
    }

    return $resultSet;
	}

	public function getCountProjectSpendingRequest($filter = array())
	{
    $select = $this->sql->select();
    $select->columns(array(
    	'count_id' => new \Zend\Db\Sql\Expression("COUNT(" . $this->tableName . ".id)"),
    ));

    $where = new \Zend\Db\Sql\Where();

    if(isset($filter['project_id']) && !empty($filter['project_id'])){
      $where->equalTo("project_id", $filter['project_id']);
    }

		if(isset($filter['is_finalized']) && !empty($filter['is_finalized'])){
      $where->equalTo("is_finalized", $filter['is_finalized']);
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
