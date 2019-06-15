<?php


namespace App\Tests\Form;


use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\Form\Test\TypeTestCase;

class CategoryTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'my test category'
        ];

        $objectToCompare = new Category();
        $form = $this->factory->create(CategoryType::class, $objectToCompare);
        $object = new Category();
        $object->setTitle('my test category');
        $form->submit($formData);
        $this->assertTrue($form->isValid());
        //$this->assertEquals($object, $objectToCompare);
        $this->assertInstanceOf(Category::class, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}