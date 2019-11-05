<?php

namespace Country\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetCountry extends AbstractHelper
{
  private $_sm;

  public function __construct(\Zend\ServiceManager\ServiceManager $sm) {
      $this->_sm = $sm;
  }

  public function getSm() {
      return $this->_sm;
  }

  public function getCountryMapper()
  {
      return $this->getSm()->get('CountryMapper');
  }

	public function __invoke($country_id)
	{
    $country = $this->getCountryMapper()->getCountry($country_id);

    return $country->getCountryName();
	}
}
