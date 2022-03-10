<?php

namespace App\Controller;

use Exception;
use App\Form\CodeType;
use Twilio\Rest\Client;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Form\SetStatusType;
use Twilio\Http\CurlClient;
use App\Form\ModifierCommandeType;
use App\Repository\CouponRepository;
use App\Repository\CommandeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Query\AST\Functions\SizeFunction;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    /**
     * @Route("/cmd", name="commande")
     */
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    /**
     * @Route("/affiche", name="affiche")
     */
    public function affiche(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    /****************************************EFFECTUER COMMANDE */
    /**
     * @Route("commander", name="commande")
     */
    function PasserCommande(Request $request, SessionInterface $session, CouponRepository $repositorycoupon)
    {

        $panier = $session->get('total');
        $commande = new Commande();
        $commande->setTotalCommande($panier);
        $form = $this->createForm(CommandeType::class, $commande);
        $coupon = $repositorycoupon->findAll();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $code = $request->request->get("code");

            $adress = $form->get('adresseLivraison')->getData();
            $rens = $form->get('renseignement')->getData();
            $modeL = $form->get('modeLivraison')->getData();
            $total = $form->get('totalCommande')->getData();
            $coupon = $repositorycoupon->findOneBy(['code' => $code]);
            if ($coupon) {
                $total = $total - $total * $coupon->getRemise() / 100;
            }
            $commande->setAdresseLivraison($adress);
            $commande->setRenseignement($rens);
            $commande->setModeLivraison($modeL);
            $commande->setTotalCommande($total);
            $this->getDoctrine()->getManager()->persist($commande);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("message", "commande succés");
            $client = new Client("AC575e044a0314036700d4bfdacff5aceb", "072c6e1972579777331c7edf67c7e026");
            $curlOptions = [CURLOPT_SSL_VERIFYHOST => false, CURLOPT_SSL_VERIFYPEER => false];
            $client->setHttpClient(new CurlClient($curlOptions));
            $client->messages->create(
                // Where to send a text message (your cell phone?)
                '+21629452990',
                array(
                    'from' => "+1 435 260 4046",
                    'body' => 'Commande faite avec succés'
                )
            );
            return $this->redirectToRoute("home");
        }

        return $this->render('commande\ajouterCommande.html.twig', ['form' => $form->createView()]);
    }




    /** CONSULTER LA LISTE DES COMMANDES */
    /**
     * @Route ("/listeCommandes", name="liste")
     */
    function listeCommandes(CommandeRepository $repository, SessionInterface $session, Request $request,  PaginatorInterface $paginator)
    {

        //$panier=$session->get('panier');
        $allcommandes = $repository->findAll();
        $commande = $paginator->paginate(
            $allcommandes,
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('commande/listeCommandes.html.twig', ['commande' => $commande]);
    }


    /****************************************MODIFIER COMMANDE */

    /**
     * @Route("updatecommande/{id}",name="updatecommande")
     */
    function ModifierCommande(CommandeRepository $repository, Request $request, $id)
    {
        //DEPEND DU STATUS DE LA COMMANDE
        $commande = $repository->find($id);
        $form = $this->createForm(ModifierCommandeType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }
        return $this->render('commande\modifierCommande.html.twig', ['form' => $form->createView()]);
    }


    /****************************************ANNULER COMMANDE */
    /**
     * @Route ("/deletecommande/{id}", name="delete")
     */
    function Delete($id, CommandeRepository $rep)
    {

        $commande = $rep->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($commande);
        $em->flush();
        return $this->redirectToRoute('liste');
    }

    /****************************************MODIFIER STATUS COMMANDE */
    /**
     * @Route("/status/{id}", name="status")
     */
    function setStatusCommande($id, CommandeRepository $rep)
    {

        $commande = $rep->find($id);
        $commande->setStatus("1");
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        return $this->redirectToRoute('listeVendeur');
    }

    /** CONSULTER LA LISTE DES COMMANDES */
    /**
     * @Route ("/listeCommandesVendeur", name="listeVendeur")
     */
    function listeCommandesVendeur(CommandeRepository $repository, SessionInterface $session, Request $request,  PaginatorInterface $paginator)
    {

        $allcommandes = $repository->findAll();
        $commande = $paginator->paginate(
            $allcommandes,
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('commande/listeCommandesVendeur.html.twig', ['commande' => $commande]);
    }
}
