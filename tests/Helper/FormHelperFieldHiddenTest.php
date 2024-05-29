<?php
/*
 * Copyright 2008 Sven Sanzenbacher
 *
 * This file is part of the naucon package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Naucon\Form\Tests\Helper;

use Naucon\Form\Helper\FormHelperFieldHidden;
use Naucon\Form\Mapper\Property;
use Naucon\Form\Tests\Entities\User;
use Naucon\Utility\Map;
use Naucon\Form\FormInterface;
use Naucon\Form\Mapper\EntityContainerInterface;
use Naucon\Form\FormCollectionInterface;
use PHPUnit\Framework\TestCase;

class FormHelperFieldHiddenTest extends TestCase
{
    public function testInit()
    {
        $userEntity = new User();
        $userEntity->setComment('foo');

        $entityContainerMap = $this->getMockBuilder(Map::class)->getMock();
        $entityContainerMap
            ->expects($this->any())
            ->method('count')
            ->willReturn(1);

        $form = $this->getMockBuilder(FormInterface::class)->getMock();
        $form
            ->expects($this->any())
            ->method('getName')
            ->willReturn('testform');
        $form
            ->expects($this->any())
            ->method('getEntityContainerMap')
            ->willReturn($entityContainerMap);

        $entityContainer = $this->getMockBuilder(EntityContainerInterface::class)->getMock();
        $entityContainer
            ->expects($this->any())
            ->method('getForm')
            ->willReturn($form);

        $entityContainer
            ->expects($this->any())
            ->method('getEntity')
            ->willReturn($userEntity);

        $propertyObject = new Property($entityContainer, 'comment');

        $formHelper = new FormHelperFieldHidden();
        $formHelper->setProperty($propertyObject);
        $formHelper->render();

        $this->assertEquals('<input type="hidden" name="testform[comment]" value="foo" />' . PHP_EOL, $formHelper->render());
    }

    public function testHelperWithOneFormCollection()
    {
        $userEntity = new User();
        $userEntity->setComment('foo');

        $entityContainerMap = $this->getMockBuilder(Map::class)->getMock();
        $entityContainerMap
            ->expects($this->any())
            ->method('count')
            ->willReturn(1);

        $form = $this->createMock(FormCollectionInterface::class);
        $form
            ->expects($this->any())
            ->method('getName')
            ->willReturn('testform');
        $form
            ->expects($this->any())
            ->method('getEntityContainerMap')
            ->willReturn($entityContainerMap);

        $entityContainer = $this->getMockBuilder(EntityContainerInterface::class)->getMock();
        $entityContainer
            ->expects($this->any())
            ->method('getForm')
            ->willReturn($form);

        $entityContainer
            ->expects($this->any())
            ->method('getName')
            ->willReturn(0);

        $entityContainer
            ->expects($this->any())
            ->method('getEntity')
            ->willReturn($userEntity);

        $propertyObject = new Property($entityContainer, 'comment');

        $formHelper = new FormHelperFieldHidden();
        $formHelper->setProperty($propertyObject);
        $formHelper->render();

        $this->assertEquals('<input type="hidden" name="testform[0][comment]" value="foo" />' . PHP_EOL, $formHelper->render());
    }
}
