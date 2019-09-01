<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


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
     * @Rest\Post("/api/users")
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
     * @Rest\Get("/api/users/{email}")
     * @param User $user
     * @return View
     */
    public function getApiUser(User $user)
    {
        return $this->view($user);
    }

    /**
     * @Rest\Patch("/api/users/{email}")
     * @param User $user
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return View
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function patchApiUser(User $user, Request $request, ValidatorInterface $validator)
    {
        $validationErrors = $validator->validate($user);
        if($validationErrors->count() > 0){
            /** @var ConstraintViolation $constraintViolation */
            foreach ($validationErrors as $constraintViolation){
                // Returns the violation message. (Ex. This value should not be blank.)
                $message = $constraintViolation->getMessage();
                // Returns the property path from the root element to the violation. (Ex. lastname)
                $propertyPath = $constraintViolation->getPropertyPath();
                $errors[] = ['message' => $message, 'propertyPath' => $propertyPath];
            }
        }

        if (!empty($errors)) {
            throw new BadRequestHttpException(\json_encode($errors));
        }

        $attributes = ['firstName' =>'setFirstName'];
        foreach ($attributes as $attributeName => $value) {
            if($request->get($attributeName) == null) {
                continue;
            }
            $user->setFirstName($request->get($attributeName));
        }

        $this->em->flush();
        return $this->view($user);
    }

    /**
     * @Rest\Delete("/api/users/{email}")
     * @param User $user
     */
    public function deleteApiUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}