<?php


namespace App\Controller\Admin;


use App\Entity\Card;
use App\Repository\CardRepository;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use FOS\RestBundle\Controller\Annotations as Rest;

class CardController extends AbstractFOSRestController
{
    private $cardRepo;
    private $em;

    public function __construct(CardRepository $cardRepo, EntityManagerInterface $em)
    {
        $this->cardRepo = $cardRepo;
        $this->em = $em;
    }


    /**
     * @Rest\Delete("/api/admin/cards/{id}")
     * @Rest\View(serializerGroups={"userProfile"})
     * @param Card $card
     */
    public function deleteApiCard(Card $card)
    {
        /** @var Card $card */
        $this->em->remove($card);
        $this->em->flush();
    }


    /**
     * @Rest\Post("/api/admin/cards/create")
     * @param Card card
     * @param ConstraintViolationListInterface $validationErrors
     * @return View
     * @Rest\View(serializerGroups={"userProfile"})
     * @ParamConverter("card", converter="fos_rest.request_body")
     */
    public function postApiCard(Card $card, ConstraintViolationListInterface $validationErrors)
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

        $this->em->persist($card);
        $this->em->flush();
        return $this->view($card);
    }


    /**
     * @Rest\Get("/api/admin/cards")
     * @Rest\View(serializerGroups={"cardIndex"})
     */
    public function getApiCards()
    {
        $cards = $this->cardRepo->findAll();
        return $this->view($cards);
    }
}

