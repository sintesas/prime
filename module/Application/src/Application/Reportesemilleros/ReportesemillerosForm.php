<?php


namespace Application\Reportesemilleros;

use Zend\Form\Form;
use Zend\Form\Element;

class ReportesemillerosForm extends Form 
{
    function __construct()
    {
       parent::__construct();
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
    }
}

