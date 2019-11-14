<?php

namespace Project\View\Helper;

use Zend\View\Helper\AbstractHelper;

use Web3\Web3;
use Web3\Contract;
use Web3\Utils;

class GetProjectAttributes extends AbstractHelper
{
	private $_sm;

  public function __construct(\Zend\ServiceManager\ServiceManager $sm) {
    $this->_sm = $sm;
  }

  public function getSm() {
    return $this->_sm;
  }

	public function __invoke($contractAddress, $fromAccount)
	{
		$milestonesCount = 0;
		$contributorsCount = 0;

		$config = $this->getSm()->get('Config');

		$web3 = new Web3($config['ethereum']['rpc']);
		$abi = $config['ethereum']['project']['abi'];
		$contract = new Contract($web3->provider, $abi);

		$contract->at($contractAddress)->call('getMilestonesCount', ['from' => $fromAccount], function($error, $result) use (&$milestonesCount){
			// print_r($error);
			// print_r($result);
			if ($error !== null) {
				throw $error;
			}
			$milestonesCount = $result[0]->value;
			// echo $milestonesCount;
		});

		$contract->at($contractAddress)->call('contributorsCount', ['from' => $fromAccount], function($error, $result) use (&$contributorsCount){
			// print_r($error);
			// print_r($result);
			if ($error !== null) {
				throw $error;
			}
			$contributorsCount = $result[0]->value;
			// echo $milestonesCount;
		});

		$results = array(
			'milestonesCount' => $milestonesCount,
			'contributorsCount' => $contributorsCount,
		);

	 	return $results;
	}
}
