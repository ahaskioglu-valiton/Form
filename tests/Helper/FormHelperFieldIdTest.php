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

use Naucon\Form\FormInterface;
use Naucon\Form\Helper\FormHelperFieldId;
use Naucon\Form\Mapper\EntityContainerInterface;
use Naucon\Form\Mapper\Property;
use Naucon\Form\Tests\Entities\User;
use Naucon\Utility\Map;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class FormHelperFieldIdTest
 *
 * @package Naucon\Form\Tests
 */
class FormHelperFieldIdTest extends TestCase
{
    /**
     * @var FormInterface|MockObject
     */
    protected $form;

    /**
     * @var EntityContainerInterface|MockObject
     */
    protected $entityContainer;


    protected function setUp(): void
    {
        parent::setUp();

        $this->form = $this->getMockBuilder(FormInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityContainer = $this->getMockBuilder(EntityContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }


    public function testInit()
    {
        $userEntity = new User();
        $userEntity->setComment('foo');

        $entityContainerMap = $this->getMockBuilder(Map::class)->getMock();
        $entityContainerMap
            ->expects($this->any())
            ->method('count')
            ->willReturn(1);

        $this->form
            ->expects($this->any())
            ->method('getName')
            ->willReturn('testform');
        $this->form
            ->expects($this->any())
            ->method('getEntityContainerMap')
            ->willReturn($entityContainerMap);


        $this->entityContainer
            ->expects($this->any())
            ->method('getForm')
            ->willReturn($this->form);

        $this->entityContainer
            ->expects($this->any())
            ->method('getEntity')
            ->willReturn($userEntity);

        $propertyObject = new Property($this->entityContainer, 'comment');

        $formHelper = new FormHelperFieldId();
        $formHelper->setProperty($propertyObject);
        $formHelper->render();

        $this->assertEquals('testform_comment', $formHelper->render());
    }
}
