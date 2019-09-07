<?php


namespace App\Controller;


use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;


class SubscriptionController extends AbstractFOSRestController
{
    private $subscriptionRepository;
    private $em;

    public function __construct(SubscriptionRepository $subscriptionRepository, EntityManagerInterface $em)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->em = $em;
    }

    /**
     * @Rest\Get("/api/subscriptions")
     */
    public function getApiSubscriptions()
    {
        $subscriptions = $this->subscriptionRepository->findAll();
        return $this->view($subscriptions);
    }


    /**
     * @Rest\Get("/api/subscriptions/{slug}")
     * @param Subscription $subscription
     * @return View
     */
    public function getApiUser(Subscription $subscription)
    {
        return $this->view($subscription);
    }
}