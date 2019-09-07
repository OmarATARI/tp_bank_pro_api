<?php


namespace App\Controller\Admin;


use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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


    /**
     * @Rest\Post("/api/admin/users/create")
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     * @return View
     * @Rest\View(serializerGroups={"userProfile"})
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postApiUser(User $user, ConstraintViolationListInterface $validationErrors)
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

        $user->setSubscription($this->subscriptionRepo->find($user->getSubscriptionId()));
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }


    /**
     * @Rest\Get("/api/admin/users")
     * @Rest\View(serializerGroups={"userIndex"})
     */
    public function getApiUsers()
    {
        $users = $this->userRepository->findAll();
        return $this->view($users);
    }
}