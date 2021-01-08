<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Message\MailNotification;
use App\Repository\TicketsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketsController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/")
     */
    public function index(): Response
    {
        $tickets = $this->entityManager->getRepository(Ticket::class)->findBy([], ['id' => 'desc']);
        return $this->render('tickets/index.html.twig', compact('tickets'));
    }

    /**
     * @Route("/tickets/add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $ticket = $form->getData();
            $this->entityManager->persist($ticket);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_tickets_index');
        }

        return $this->render('tickets/add.html.twig', [
            'form' => $form->createView(),
            'btnName' => "Add",
            'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/tickets/edit/{id}")
     * @param Request $request
     * @param Ticket $ticket
     * @return Response
     */
    public function edit(Request $request, Ticket $ticket): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();

            return $this->redirectToRoute('app_tickets_index');
        }

        return $this->render('tickets/add.html.twig', [
            'form' => $form->createView(),
            'btnName' => "Edit",
            'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/tickets/{id}", methods={"DELETE"})
     * @param Request $request
     * @param Ticket $ticket
     * @return Response
     */
    public function delete(Request $request, Ticket $ticket): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tickets_index');
    }

    /**
     * @Route("/tickets/send/{id}")
     * @param Ticket $ticket
     * @return Response
     */
    public function send(Ticket $ticket): Response
    {
        $this->addFlash('success', "Notification send");
        $notification = new MailNotification($ticket->getId(), $ticket->getTitle(), $ticket->getDescription(), $ticket->getCreatedAt());
        $this->dispatchMessage($notification);
        return $this->redirectToRoute('app_tickets_index');
    }
}
