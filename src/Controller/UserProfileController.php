<?php


namespace App\Controller;


use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserProfileController extends AbstractFOSRestController
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


    // ==============================           ANONYMOUS USER PART

    /**
     * @Rest\Post("/api/users/create")
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
     * @Rest\View(serializerGroups={"userIndex"})
     */
    public function getApiUser(User $user)
    {
        return $this->view($user);
    }

    // ==============================           AUTHENTICATED USER PART

    /**
     * @Rest\Patch("/api/profile/{email}")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @Rest\View(serializerGroups={"user"})
     * @return View
     */
    public function patchApiUser(Request $request, ValidatorInterface $validator)
    {
        $user = $this->getUser();
        $attributes = ['firstname' => 'setFirstname',
            'lastname' => 'setLastname',
            'address' => 'setAddress',
            'country' => 'setCountry',
            'email' => 'setEmail'];

        foreach($attributes as $attribute => $setter) {
            if(is_null($request->get($attribute))) {
                continue;
            }
            $user->$setter($request->request->get($attribute));
        }

        if($request->get('subscription')) {
            $subscription = $this->em->getRepository(Subscription::class)->findOneBy(['id' => $request->request->get('subscription')]);
            if(is_null($subscription)) {
                throw new NotFoundHttpException('this subscription does not exist');
            } else {
                $user->setSubscription($subscription);
            }
        }
        $validationErrors = $validator->validate($user);
        if($validationErrors->count() > 0) {
            /** @var ConstraintViolation $constraintViolation */
            foreach ($validationErrors as $constraintViolation) {
                $message = $constraintViolation->getMessage();
                $propertyPath = $constraintViolation->getPropertyPath();
                $errors[] = ['message' => $message, 'propertyPath' => $propertyPath];
            }
        }
        if (!empty($errors)) {
            throw new BadRequestHttpException(json_encode( $errors));
        }
        $this->em->flush();
        return $this->view($user, Response::HTTP_ACCEPTED);
    }

}