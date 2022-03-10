<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Form\CouponType;
use App\Repository\CouponRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CouponController extends AbstractController
{
    /**
     * @Route("/coupon", name="coupon")
     */
    public function index(): Response
    {
        return $this->render('coupon/index.html.twig', [
            'controller_name' => 'CouponController',
        ]);
    }

                            /****************************************AJOUTER CODE COUPON */
  /**
     * @Route("ajoutercode", name="code")
     */
            function AjouterCode(Request $request){
                    
                $code=new Coupon();
                $form = $this->createForm(CouponType::class, $code);
                $form->handleRequest($request);
                if($form->isSubmitted()&& $form->isValid()){
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($code);
                    $em->flush();
                    return $this->redirectToRoute('listecoupons');
            }
                return $this->render('coupon\ajouterCode.html.twig',['form'=>$form->createView()]);
            }


                                            /** CONSULTER LA LISTE DES CODES */
    /**
     * @Route ("/listecoupons", name="listecoupons")
     */
            function listeCodes(CouponRepository $repository, Request $request,  PaginatorInterface $paginator){

                $allcodes=$repository->findAll();
                $code=$paginator->paginate(
                    $allcodes,
                    $request->query->getInt('page',1),
                    2
                );
                return $this->render('coupon\listeCodeCoupon.html.twig',['code'=>$code]);

            }


                            /****************************************MODIFIER COMMANDE */
                                            
    /**
     * @Route("updatecode/{id}",name="updatecode")
     */
            function ModifierCode(CouponRepository $repository, Request $request, $id){

                $code=$repository->find($id);
                $form = $this->createForm(CouponType::class, $code);
                $form->handleRequest($request);
                if($form->isSubmitted()&& $form->isValid()){
                    $em=$this->getDoctrine()->getManager();
                    $em->flush();
                    return $this->redirectToRoute('listecoupons');
                    
            }
                return $this->render('coupon\modifierCode.html.twig',['form'=>$form->createView()]);
            }


                            /****************************************SUPPRIMER COMMANDE */        
    /**
     * @Route ("/deletecode/{id}", name="deletecode")
     */
            function Delete($id,CouponRepository $rep){
                $code=$rep->find($id);
                $em=$this->getDoctrine()->getManager();
                $em->remove($code);
                $em->flush();
                return $this->redirectToRoute('listecoupons');

            }

        /**
         * @Route("/apply", name="apply")
         */
            function appliquerCoupon(SessionInterface $session){

                $total=$session->get('total');
            }
}
