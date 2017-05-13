<?php

namespace Site\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\TestBundle\Form\UsersType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Site\TestBundle\Entity\Users;
use Symfony\Component\Form\FormError;

class UsersController extends Controller
{
    /*
     * Display list of Users
     */
    public function ShowListAction()
    {
        $qb = $this->getDoctrine()->getRepository('SiteTestBundle:Users')->createQueryBuilder('c');

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate( $qb, $this->container->get('request_stack')->getCurrentRequest()->query->get('page', 1),10);

        $page=(int)$this->container->get('request_stack')->getCurrentRequest()->get('page');
        
        
        return $this->render('SiteTestBundle:Users:ShowList.html.twig',array('pagination' => $pagination,'page'=>$page));
    }
    
    /*
     * Edit or Add new User
     */
    public function EditAction($id)
    {
        if ($id){
            $User = $this->getDoctrine()->getRepository('SiteTestBundle:Users')->findOneById($id);
        }else{
            $User = new Users(); 
        }
        $request=$this->container->get('request_stack')->getCurrentRequest();
        
        $req_path=$request->get('req_path');
        if(!$req_path){
            $req_path = $this->get('router')->generate('_show_users');
        }

        
        $orgid=(int)$request->get('orgid');
        $Organization=null;
        if($orgid){
            $Organization = $this->getDoctrine()->getRepository('SiteTestBundle:Organizations')->findOneById($orgid);
            $User->setOrgid($Organization);
        }
   
        
        
        $form = $this->createForm(UsersType::class, $User);

        $form->handleRequest($request);        
        if ($form->isSubmitted() && $form->isValid()) {
            $validator = $this->get('validator');
            $errors = $validator->validate($User);
            foreach ($errors as $error){
                 $form->get($error->getPropertyPath())->addError(new FormError($error->getMessage()));
            }
        
            if ((count($errors)==0) && $form->isValid()) {
                $this->SaveDb($User,'Данные сохранены!');
                return new RedirectResponse($req_path);
            }
        }

        return $this->render('SiteTestBundle:Users:Edit.html.twig', array('form' => $form->createView(),'req_path'=>$req_path));
        
    }
    
    /*
     * Function save data to entity
     */
    public function SaveDb($data,$mes){
        $em = $this->getDoctrine()->getEntityManager();
        try {
                $em->persist($data);
                $em->flush();
                $this->get('session')->getFlashBag()->add('alert', $mes); 
            }catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', 'Ошибка: '. $e->getMessage());
            }
    }
    
    
    /*
     * Delete Array or one Organization 
     */
    public function DeletAction($id)
    {
        $request=$this->container->get('request_stack')->getCurrentRequest();
        if($id){
           $this->Delete($id);
        }else{
            foreach ($request->get('chb') as $value) { $this->Delete($value);}
        }
        
        $req_path=$request->get('req_path');
        if(!$req_path){
            $req_path = $this->get('router')->generate('_show_users');
        }
        return new RedirectResponse($req_path);
        
    }
    
    /*
     * Delete data from entity
     */
    public function Delete($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $Users= $em->getRepository('SiteTestBundle:Users')->findOneById($id);
        if ($Users) {
            try {
                $em->remove($Users);
                $em->flush();
                $this->get('session')->getFlashBag()->add('alert', 'Данные Удалены!'); 
                }catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('error', 'Ошибка: '. $e->getMessage());
                }
        }
    }
}
