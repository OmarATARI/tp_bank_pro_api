<?php


namespace App\Controller\Admin;


use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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
     * @Rest\Post("/api/admin/subscriptions/create")
     * @param Subscription $subscription
     * @param ConstraintViolationListInterface $validationErrors
     * @return View
     * @ParamConverter("subscription", converter="fos_rest.request_body")
     */
    public function postApiSubscription(Subscription $subscription, ConstraintViolationListInterface $validationErrors)
    {
        $errors = array();

        if ($validationErrors->count() > 0) {
            /** @var ConstraintViolation $constraintViolation */
            foreach ($validationErrors as $constraintViolation){
                $message = $constraintViolation->getMessage();
                $propertyPath = $constraintViolation->getPropertyPath();
                $errors[] = ['message' => $message, 'propertyPath' => $propertyPath];
            }
        }
        if (!empty($errors)) {
            throw new BadRequestHttpException(\json_encode($errors));
        }

        $subscription->setSlug(strtolower(
            str_replace(' ', '', $subscription->getName())
        ));
        $this->em->persist($subscription);
        $this->em->flush();
        return $this->view($subscription);
    }
}