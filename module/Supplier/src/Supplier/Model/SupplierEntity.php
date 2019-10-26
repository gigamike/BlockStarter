<?php
namespace Supplier\Model;

class SupplierEntity
{
	protected $id;
	protected $name;
	protected $description;
	protected $eos_public_address;
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

	public function getName()
	{
		return $this->name;
	}

	public function setName($value)
	{
		$this->name = $value;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($value)
	{
		$this->description = $value;
	}

	public function getEosPublicAddress()
	{
		return $this->eos_public_address;
	}

	public function setEosPublicAddress($value)
	{
		$this->eos_public_address = $value;
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
