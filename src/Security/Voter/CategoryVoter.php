<?php

namespace App\Security\Voter;

use App\Entity\Category;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CategoryVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof Category;
    }

    protected function voteOnAttribute($attribute, $category, TokenInterface $token)
    {
        /** @var Category $category */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                if($category->getUser() === $user) {
                    return true;
                }
                break;
            case 'DELETE':
                if($category->getUser() === $user) {
                    return true;
                }
                break;
        }

        return false;
    }
}
