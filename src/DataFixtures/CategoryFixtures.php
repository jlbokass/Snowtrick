<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends BaseFixture implements  DependentFixtureInterface
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

            $category->setUser($this->getRandomReference(User::class));
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
    ];
    }
}
