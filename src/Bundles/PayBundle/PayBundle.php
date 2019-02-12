<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-6-15
 * Time: 上午11:48
 */

namespace Bundles\PayBundle;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PayBundle extends Bundle
{
	const VERSION = '1.0.0';

	public function build(ContainerBuilder $container)
	{
		/*$container->addCompilerPass(new EasyAdminFormTypePass(), PassConfig::TYPE_BEFORE_REMOVING);
		$container->addCompilerPass(new EasyAdminConfigPass());*/
	}
}

//class_alias('EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle', 'JavierEguiluz\Bundle\EasyAdminBundle\EasyAdminBundle', false);