<?php

namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message as Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\Sendmail as Sendmail;

class IndexController extends AbstractActionController
{
  /*
  *
  * /Applications/MAMP/bin/php/php7.1.31/bin/php /Users/michaelgerardgalon/Sites/hackathons/uhack2019.gigamike.net/public_html/index.php cron-test
  * /usr/bin/php /var/www/dish2019.gigamike.net/public_html/index.php cron-test
  */
  public function indexAction()
  {
  
	}
}
