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

use Naucon\Form\FormCollectionInterface;
use Naucon\Form\FormInterface;
use Naucon\Form\Helper\FormHelperFieldTextarea;
use Naucon\Form\Mapper\EntityContainerInterface;
use Naucon\Form\Mapper\Property;
use Naucon\Form\Tests\Entities\User;
use Naucon\Utility\Map;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class FormHelperFieldTextareaTest
 *
 * @package Naucon\Form\Tests
 */
class FormHelperFieldTextareaTest extends TestCase
{
    /**
     * @var FormInterface|MockObject
     */
    protected $form;

    /**
     * @var FormCollectionInterface|MockObject
     */
    protected $formCollection;

    /**
     * @var EntityContainerInterface|MockObject
     */
    protected $entityContainer;

    /**
     * @var Map|MockObject
     */
    protected $entityContainerMap;

    protected function setUp(): void
    {
        parent::setUp();

        $this->form = $this->getMockBuilder(FormInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->formCollection = $this->getMockBuilder(FormCollectionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityContainer = $this->getMockBuilder(EntityContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityContainerMap = $this->getMockBuilder(Map::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testInit()
    {
        $userEntity = new User();
        $userEntity->setComment('foo');

        $this->entityContainerMap->expects($this->any())
            ->method('count')
            ->willReturn(1);


        $this->form->expects($this->any())
            ->method('getName')
            ->willReturn('testform');

        $this->form->expects($this->any())
            ->method('getEntityContainerMap')
            ->willReturn($this->entityContainerMap);


        $this->entityContainer->expects($this->any())
            ->method('getForm')
            ->willReturn($this->form);

        $this->entityContainer->expects($this->any())
            ->method('getEntity')
            ->willReturn($userEntity);

        $propertyObject = new Property($this->entityContainer, 'comment');

        $formHelper = new FormHelperFieldTextarea();
        $formHelper->setProperty($propertyObject);
        $formHelper->render();

        $this->assertEquals('<textarea name="testform[comment]" id="testform_comment">foo</textarea>' . PHP_EOL, $formHelper->render());
    }

    public function testHelperWithOneFormCollection()
    {
        $userEntity = new User();
        $userEntity->setComment('foo');

        $this->entityContainerMap->expects($this->any())
            ->method('count')
            ->willReturn(1);


        $this->formCollection->expects($this->any())
            ->method('getName')
            ->willReturn('testform');

        $this->formCollection->expects($this->any())
            ->method('getEntityContainerMap')
            ->willReturn($this->entityContainerMap);


        $this->entityContainer->expects($this->any())
            ->method('getForm')
            ->willReturn($this->formCollection);

        $this->entityContainer->expects($this->any())
            ->method('getName')
            ->willReturn(0);

        $this->entityContainer->expects($this->any())
            ->method('getEntity')
            ->willReturn($userEntity);

        $propertyObject = new Property($this->entityContainer, 'comment');

        $formHelper = new FormHelperFieldTextarea();
        $formHelper->setProperty($propertyObject);
        $formHelper->render();

        $this->assertEquals('<textarea name="testform[0][comment]" id="testform_0_comment">foo</textarea>' . PHP_EOL, $formHelper->render());
    }

    public function testRenderWithValueAttribute()
    {
        $userEntity = new User();

        $this->entityContainerMap->expects($this->any())
            ->method('count')
            ->willReturn(1);


        $this->form->expects($this->any())
            ->method('getName')
            ->willReturn('testform');

        $this->form->expects($this->any())
            ->method('getEntityContainerMap')
            ->willReturn($this->entityContainerMap);


        $this->entityContainer->expects($this->any())
            ->method('getForm')
            ->willReturn($this->form);

        $this->entityContainer->expects($this->any())
            ->method('getEntity')
            ->willReturn($userEntity);

        $propertyObject = new Property($this->entityContainer, 'comment');

        $formHelper = new FormHelperFieldTextarea();
        $formHelper->setProperty($propertyObject);
        $formHelper->setOptions([
           'value' => 'bar'
        ]);
        $formHelper->render();

        $this->assertEquals('<textarea name="testform[comment]" id="testform_comment">bar</textarea>' . PHP_EOL, $formHelper->render());
    }

    public function testRenderWithWhitelistedAttributes()
    {
        $userEntity = new User();
        $userEntity->setComment('foo');

        $this->entityContainerMap->expects($this->any())
            ->method('count')
            ->willReturn(1);


        $this->form->expects($this->any())
            ->method('getName')
            ->willReturn('testform');

        $this->form->expects($this->any())
            ->method('getEntityContainerMap')
            ->willReturn($this->entityContainerMap);


        $this->entityContainer->expects($this->any())
            ->method('getForm')
            ->willReturn($this->form);

        $this->entityContainer->expects($this->any())
            ->method('getEntity')
            ->willReturn($userEntity);

        $propertyObject = new Property($this->entityContainer, 'comment');

        $formHelper = new FormHelperFieldTextarea();
        $formHelper->setProperty($propertyObject);
        $formHelper->setOptions([
            'id' => 'foo2',
            'class' => 'form-control'
        ]);
        $formHelper->render();

        $this->assertEquals('<textarea name="testform[comment]" id="foo2" class="form-control">foo</textarea>' . PHP_EOL, $formHelper->render());
    }
}
