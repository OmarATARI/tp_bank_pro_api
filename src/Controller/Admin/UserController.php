<?php


namespace App\Controller\Admin;


use App\Entity\User;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends AbstractFOSRestController
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
     * @Rest\Delete("/api/admin/users/{email}")
     * @Rest\View(serializerGroups={"userProfile"})
     * @param User $user
     */
    public function deleteApiUser(User $user)
    {
        /** @var User $user */
        $this->em->remove($user);
        $this->em->flush();
    }

}