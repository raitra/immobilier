<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserService
{
    private $entityManager;
    private $mailer;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->entityManager = $em;
        $this->mailer = $mailer;
    }

    public function doesUserExist(string $username): bool
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $username]);
        return $user !== null;
    }

    public function sendEmailToUser(User $user): void
    {
        $email = (new Email())
            ->from('misaina@raketa.mg')
            ->to($user->getEmail())
            ->subject('Jeton de sécurité')
            ->text('Voici ton jeton: 665GU');
        
        $this->mailer->send($email);

    }
}