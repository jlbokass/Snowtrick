<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends BaseFixture
{
    private static $categoryTitle = [
        'Straight airs',
        'Grabs',
        'Spins',
        'Flips and inverted rotations',
        'Inverted hand plants',
        'Slides',
    ];

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Category::class,6, function (Category $category) {

            $category->setCategoryName($this->faker->unique()->randomElement(self::$categoryTitle));
            $category->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
        });

        $manager->flush();
    }
}
