<?php
/**
 * Copyright (c) 2014 Roave, LLC.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Antoine Hedgecock
 *
 * @copyright 2014 Roave, LLC
 * @license http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

namespace Roave\EmailTemplates\Factory\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Roave\EmailTemplates\Hydrator\TemplateHydrator;
use Roave\EmailTemplates\InputFilter\TemplateInputFilter;
use Roave\EmailTemplates\Options\TemplateServiceOptions;
use Roave\EmailTemplates\Repository\TemplateRepository;
use Roave\EmailTemplates\Service\Template\EnginePluginManager;
use Roave\EmailTemplates\Service\Template\Listener\UpdateTemplateParametersListener;
use Roave\EmailTemplates\Service\TemplateService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TemplateServiceFactory
 *
 * Creates the TemplateService {@see \Roave\EmailTemplates\Service\TemplateService}
 */
class TemplateServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @throws Exception\ConfigurationException
     * @return \Roave\EmailTemplates\Service\
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new TemplateService(
            $serviceLocator->get('Roave\EmailTemplates\ObjectManager'),
            $serviceLocator->get(TemplateRepository::class),
            $serviceLocator->get('inputFilterManager')->get(TemplateInputFilter::class),
            $serviceLocator->get('hydratorManager')->get(TemplateHydrator::class),
            $serviceLocator->get(EnginePluginManager::class),
            $serviceLocator->get(TemplateServiceOptions::class)
        );

        $service
            ->getEventManager()
            ->attach($serviceLocator->get(UpdateTemplateParametersListener::class));

        return $service;
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return $this->createService($container);
    }
}
