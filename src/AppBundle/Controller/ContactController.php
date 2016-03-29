<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/contact")
 */
class ContactController extends Controller
{

    /**
     * @Route("", name="contact")
     * @return Response
     */
    public function contactAction()
    {
        return $this->render('AppBundle:Contact:contact.html.twig');
    }

    /**
     * @Route("/send", name="sendEmail")
     * @return Response
     */
    public function sendEmailAction(Request $request)
    {
        $email = $request->get('email');
        $message = $request->get('message');
        $subject = $request->get('subject');

        $swiftMessage = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($email)
            ->setTo($this->getParameter('mailer_user'))
            ->setBody($this->renderView('AppBundle:Contact:email.html.twig',
                ['email' => $email, 'subject' => $subject, 'message' => $message]))
            ->setContentType('text/html');

        $response = $this->get('mailer')->send($swiftMessage);

        return new JsonResponse(['nextUri' => $this->generateUrl('emailSent'), 'response' => $response]);
    }

    /**
     * @Route("/emailSent", name="emailSent")
     * @return Response
     */
    public function emailSentAction()
    {
        return $this->render('AppBundle:Contact:emailSent.html.twig');
    }

}
