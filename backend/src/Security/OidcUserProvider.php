<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AttributesBasedUserProviderInterface;

class OidcUserProvider implements AttributesBasedUserProviderInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function loadUserByIdentifier(string $identifier, array $attributes = []): UserInterface
    {
        $email = $identifier;

        $repo = $this->em->getRepository(User::class);

        $user = $repo->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setName($attributes['name'] ?? $attributes['preferred_username'] ?? 'Unknown');
        }

        $roles = ['ROLE_USER'];
        if (isset($attributes['realm_access']['roles'])) {
            foreach ($attributes['realm_access']['roles'] as $role) {
                $roles[] = 'ROLE_'.strtoupper($role);
            }
        }
        $user->setRoles(array_unique($roles));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    // -------------------------
    // Required by UserProviderInterface
    // -------------------------

    public function refreshUser(UserInterface $user): UserInterface
    {
        // Since we use stateless JWT authentication, we don't need to reload users
        // Just return the user
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }
}
