<?php
namespace Project\Model;

class ProjectSpendingRequestEntity
{
	protected $id;
	protected $project_id;
	protected $description;
	protected $amount;
	protected $supplier_id;
	protected $is_finalized;
	protected $created_datetime;

	public function __construct()
	{
		$this->created_datetime = date('Y-m-d H:i:s');
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($value)
	{
		$this->id = $value;
	}

	public function getProjectId()
	{
		return $this->project_id;
	}

	public function setProjectId($value)
	{
		$this->project_id = $value;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($value)
	{
		$this->description = $value;
	}

	public function getAmount()
	{
		return $this->amount;
	}

	public function setAmount($value)
	{
		$this->amount = $value;
	}

	public function getSupplierId()
	{
		return $this->supplier_id;
	}

	public function setSupplierId($value)
	{
		$this->supplier_id = $value;
	}

	public function getIsFinalized()
	{
		return $this->is_finalized;
	}

	public function setIsFinalized($value)
	{
		$this->is_finalized = $value;
	}

	public function getCreatedDatetime()
	{
		return $this->created_datetime;
	}

	public function setCreatedDatetime($value)
	{
		$this->created_datetime = $value;
	}
}
