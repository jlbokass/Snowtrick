<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixture implements DependentFixtureInterface
{
    private static $articleTitle = [
        'BackFlip',
        'The HeelClicker',
        'The Superman',
        'The Double Cancan',
        'Backside Triple Cork 1440',
        'Method Air',
        'Double Backflip One Foot',
        'Double Mc Twist 1260',
        'Double Backside Rodeo 1080',
        'Cork',
        'Revert',
        'The Switch',
        'Backside Air',
        'Crippler',
        'Handplant',
        'The 270',
        'Air to Fakie',
        'Backside Rodeo',
    ];

    private static $articleContents = [
    ];

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Article::class,17, function (Article $article) {
        $article->setTitle($this->faker->unique()->randomElement(self::$articleTitle))
            ->setContent($this->faker->paragraph(10, true));

        // publish most articles
        if ($this->faker->boolean(70)) {
            $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
        }

        /** @var Category[] $category */
        $category = $this->getRandomReferences(Category::class, $this->faker->numberBetween(1,6));

        foreach ($category as $category) {
            $article->setCategory($category);
        }

        /** @var User[] $user */
        $user = $this->getRandomReferences(User::class, $this->faker->numberBetween(1,2));

        foreach ($user as $user) {
            $article->setUser($user);
        }
    });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            CategoryFixtures::class,
        ];
    }
}
