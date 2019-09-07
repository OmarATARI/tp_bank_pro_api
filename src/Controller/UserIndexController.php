<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;


class UserIndexController extends AbstractFOSRestController
{
    private $userRepository;
    private $subscriptionRepo;
    private $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em, SubscriptionRepository $user_subscription)
    {
        $this->userRepository = $userRepository;
        $this->subscriptionRepo = $user_subscription;
        $this->em = $em;
    }

    /**
     * @Rest\Get("/api/users")
     * @Rest\View(serializerGroups={"userIndex"})
     */
    public function getApiUsers()
    {
        $users = $this->userRepository->findAll();
        return $this->view($users);
    }

    /**
     * @Rest\Get("/api/users/{email}")
     * @param User $user
     * @return View
     * @Rest\View(serializerGroups={"userIndex"})
     */
    public function getApiUser(User $user)
    {
        return $this->view($user);
    }
}