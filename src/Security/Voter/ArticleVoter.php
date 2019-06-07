<?php

namespace App\Security\Voter;

use App\Entity\Article;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof Article;
    }

    protected function voteOnAttribute($attribute, $article, TokenInterface $token)
    {
        /** @var Article $article */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':

                if ($article->getUser() === $user) {
                    return true;
                }

                break;

            case 'DELETE':

                if ($article->getUser() === $user) {
                    return true;
                }
                break;
        }

        return false;
    }
}
