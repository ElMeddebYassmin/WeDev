<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    // /**
    //  * @Route("/mailer", name="mailer")
    //  */
    // function Contact(Request $request,\Swift_Mailer $mailer ){
    //        $form= $this->createForm(ContactType::class);
    //         $form->handleRequest($request);
    //         if($form->isSubmitted() && $form->isValid()){
    //             $contact=$form->getData();
    //             $message= (new \Swift_Message('Contact'))
    //             ->setFrom('meddebyesmina123@gmail.com')
    //             //->setTo(app.$user = $this->getUser())
    //             ->setTo('yassmin.mouaddeb@esprit.tn')
    //             ->setBody($this->renderView('contact/index.html.twig'));
    //             $mailer->send($message);
    //         }
    //         return $this->render('contact/index.html.twig',['formContact'=>$form->createView()]);

    // }

    
}
