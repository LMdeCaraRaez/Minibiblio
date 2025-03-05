<?php

namespace App\Security;

use App\Entity\Libro;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class LibroVoter extends Voter
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    const LIST = "LIBRO_LIST";
    const VIEW = "LIBRO_VIEW";
    const DELETE = "LIBRO_DELETE";
    const EDIT = "LIBRO_EDIT";



    protected function supports(string $attribute, $subject): bool
    {

        if (!in_array($attribute, [self::LIST, self::VIEW, self::DELETE, self::EDIT], true)) {
            return false;
        }

        if (!$subject instanceof Libro) {
            return false;
        }


        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$subject instanceof Libro) {
            return false;
        }

        switch ($attribute) {
            case self::LIST:
                return $this->canList();
            case self::DELETE:
                return $this->security->isGranted("ROLE_ADMIN") && $subject->getSocio() === null;
            case self::VIEW:
                return $this->canView();
            case self::EDIT:
                return $this->security->isGranted("ROLE_BIBLIOTECARIO") || $this->security->isGranted("ROLE_DOCENTE") && $user === $subject->getSocio();
        }

        return false;
    }
}