<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article")
     */
    public function new(EntityManagerInterface $manager)
    {
        $article = new Article();
        $article->setTitle('Trick-'. rand(100,999))
            ->setSlug('link-for-trick-'. rand(100, 999))
            ->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer sit amet purus nec 
            diam feugiat consectetur a accumsan orci. Cras tincidunt ex ipsum, vitae tincidunt mi sollicitudin sit amet.
             Duis fermentum elit vitae metus malesuada vehicula. Mauris interdum, neque sit amet posuere ultricies,
              metus augue pellentesque eros, et eleifend nunc justo sed nisl. Quisque tempus venenatis eros, faucibus 
              ullamcorper sapien vehicula id. Donec ac mollis sem. Morbi tempus semper metus ut posuere. Aliquam mi 
              nisl, ultrices a augue in, ultricies fringilla augue. Vestibulum ornare mollis risus, ultricies blandit 
              tellus porta vitae.');

        // publish most articles
        if (rand(1, 10) > 2) {

            $article->setPublishedAt(new \DateTime(sprintf('-%d day', rand(1, 100))));
        }

        $manager->persist($article);
        $manager->flush();

        return new Response(sprintf(
            'Hiya! new Article id: #%d slug: %s',
            $article->getId(),
            $article->getSlug()
        ));

    }
}
