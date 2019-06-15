<?php

namespace App\Security\Voter;

use App\Entity\Comment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof Comment;
    }

    protected function voteOnAttribute($attribute, $comment, TokenInterface $token)
    {
        /** @var Comment $comment */
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
                if ($comment->getUser() === $user) {
                    return true;
                }
                break;
            case 'DELETE':
                if ($comment->getUser() === $user) {
                    return true;
                }
                break;
        }

        return false;
    }
}
