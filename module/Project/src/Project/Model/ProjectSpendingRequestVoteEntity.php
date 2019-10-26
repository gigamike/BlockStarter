<?php
namespace Project\Model;

class ProjectSpendingRequestVoteEntity
{
	protected $id;
	protected $project_spending_request_id;
	protected $eos_public_address;
	protected $is_approved;
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

	public function getProjectSpendingRequestId()
	{
		return $this->project_spending_request_id;
	}

	public function setProjectSpendingRequestId($value)
	{
		$this->project_spending_request_id = $value;
	}

	public function getEosPublicAddress()
	{
		return $this->eos_public_address;
	}

	public function setEosPublicAddress($value)
	{
		$this->eos_public_address = $value;
	}

	public function getIsApproved()
	{
		return $this->is_approved;
	}

	public function setIsApproved($value)
	{
		$this->is_approved = $value;
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
