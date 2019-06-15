<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixture extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Comment::class, 100, function (Comment $comment) {
            $comment->setContent(
               $this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(4, true)
           );

            $comment->setCreatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'));

            /** @var User[] $user */
            $user = $this->getRandomReferences(User::class, $this->faker->numberBetween(1, 2));

            foreach ($user as $user) {
                $comment->setUser($user);
            }

            /** @var Article[] $user */
            $article = $this->getRandomReferences(Article::class, $this->faker->numberBetween(1, 4));
            foreach ($article as $article) {
                $comment->setArticle($article);
            }
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            ArticleFixtures::class,
        ];
    }
}
