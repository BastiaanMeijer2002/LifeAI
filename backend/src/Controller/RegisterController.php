<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;

final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function index(
        Request $request, 
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $json = json_decode($request->getContent(), true);

        // Validate input
        if (empty($json['email']) || empty($json['password']) || empty($json['name'])) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        // Check if user already exists
        $existingUser = $entityManager->getRepository(User::class)
            ->findOneBy(['email' => $json['email']]);

        if ($existingUser) {
            return new JsonResponse(['error' => 'Email already registered'], 409);
        }

        // Create new User
        $user = new User();
        $user->setEmail($json['email']);
        $user->setName($json['name']);
        $user->setPassword(
            $passwordHasher->hashPassword($user, $json['password'])
        );

        // Save to database
        $entityManager->persist($user);
        $entityManager->flush();

        // Return success response
        return new JsonResponse([
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName()
            ]
        ], 201);
    }
}
