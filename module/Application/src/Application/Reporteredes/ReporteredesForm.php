<?php


namespace Application\Reporteredes;

use Zend\Form\Form;
use Zend\Form\Element;

class ReporteredesForm extends Form 
{
    function __construct()
    {
       parent::__construct();
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
    }
}

