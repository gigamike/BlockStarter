<?php
namespace Project\Model;

class ProjectContributorEntity
{
	protected $id;
	protected $project_id;
	protected $eos_public_address;
	protected $amount;
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

	public function getEosPublicAddress()
	{
		return $this->eos_public_address;
	}

	public function setEosPublicAddress($value)
	{
		$this->eos_public_address = $value;
	}

	public function getAmount()
	{
		return $this->amount;
	}

	public function setAmount($value)
	{
		$this->amount = $value;
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
