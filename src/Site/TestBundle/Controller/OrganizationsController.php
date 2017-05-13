<?php

namespace Site\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\TestBundle\Form\OrganizationsType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Site\TestBundle\Entity\Organizations;
use Symfony\Component\Form\FormError;


class OrganizationsController extends Controller
{
    /*
     * Display list of organizations
     */
    public function ShowListAction()
    {
        $qb = $this->getDoctrine()->getRepository('SiteTestBundle:Organizations')->createQueryBuilder('c');
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate( $qb, $this->container->get('request_stack')->getCurrentRequest()->query->get('page', 1),10);
 
        return $this->render('SiteTestBundle:Organizations:ShowList.html.twig',array('pagination' => $pagination));
    }
    
    /*
     * Display list Users of selected Organization
     */
    public function ShowOrganizationUsersAction($id)
    {
        $Organization=$this->getDoctrine()->getRepository('SiteTestBundle:Organizations')->findOneById($id);
        $qb = $this->getDoctrine()->getRepository('SiteTestBundle:Users')->createQueryBuilder('c');
        $qb->where('c.orgid = '.$id); 

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate( $qb, $this->container->get('request_stack')->getCurrentRequest()->query->get('page', 1),10);

        return $this->render('SiteTestBundle:Users:ShowList.html.twig',array('organization'=>$Organization ,'pagination' => $pagination));
    }
    
    /*
     * Edit or Add new Organization
     */
    public function EditAction($id)
    {
        if ($id){
            $Organization = $this->getDoctrine()->getRepository('SiteTestBundle:Organizations')->findOneById($id);
        }else{
            $Organization = new Organizations(); 
        }
        $request=$this->container->get('request_stack')->getCurrentRequest();
        
        $req_path=$request->get('req_path');
        if(!$req_path){
            $req_path = $this->get('router')->generate('site_test_homepage');
        }
        
        $form = $this->createForm(OrganizationsType::class, $Organization);
        $form->handleRequest($request);        
        if ($form->isSubmitted()) { 
            $validator = $this->get('validator');
            $errors = $validator->validate($Organization);
            foreach ($errors as $error){
                 $form->get($error->getPropertyPath())->addError(new FormError($error->getMessage()));
            }
        
            if ((count($errors)==0) && $form->isValid()) {
                $this->SaveDb($Organization,'Данные сохранены!');
                return new RedirectResponse($req_path);
            }
        }
        
        return $this->render('SiteTestBundle:Organizations:Edit.html.twig', array('form' => $form->createView(),'req_path'=>$req_path));
        
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
                }catch (\PDOException $e) {
                    $this->get('session')->getFlashBag()->add('error', 'Не удалось изменить!'); 
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
            $req_path = $this->get('router')->generate('site_test_homepage');
        }
        return new RedirectResponse($req_path);
    }
    
    /*
     * Delete data from entity
     */
    public function Delete($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $Organization = $em->getRepository('SiteTestBundle:Organizations')->findOneById($id);
        if ($Organization) {
            try {
                $em->remove($Organization);
                $em->flush();
                $this->get('session')->getFlashBag()->add('alert', 'Данные Удалены!');
                }catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('error', 'Ошибка: '. $e->getMessage());
                } 
        }
    }
}
