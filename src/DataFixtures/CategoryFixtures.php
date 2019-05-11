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

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Category::class,6, function (Category $category) {

            $category->setTitle($this->faker->unique()->randomElement(self::$categoryTitle));
            $category->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $category->setContent($this->faker->unique()->paragraph);
            $category->setAuthor($this->faker->name);

            // publish most articles
            if ($this->faker->boolean(90)) {

                $category->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }
        });

        $manager->flush();
    }
}
