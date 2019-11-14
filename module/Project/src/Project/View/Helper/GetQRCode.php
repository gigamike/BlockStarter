<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetQRCode extends AbstractHelper
{
	private $_sm;

  public function __construct(\Zend\ServiceManager\ServiceManager $sm) {
    $this->_sm = $sm;
  }

  public function getSm() {
    return $this->_sm;
  }

	public function __invoke($address, $width, $height)
	{
		$url = "https://chart.googleapis.com/chart?chs=" . $width . "x" . $height . "&cht=qr&chl=" . $address . "&choe=UTF-8";

	 	return $url;
	}
}
