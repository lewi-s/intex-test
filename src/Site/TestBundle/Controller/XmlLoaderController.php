<?php

namespace Site\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\TestBundle\Form\UsersType;
use Site\TestBundle\Form\OrganizationsType;
use Site\TestBundle\Entity\Organizations;
use Site\TestBundle\Entity\Users;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\DomCrawler\Crawler;

class XmlLoaderController extends Controller
{
    private $countAddUsers;
    private $countAddOrganizations;


    /*
     * Creating and handling form for import xml file
     */
    public function LoadAction()
    {
        $request=$this->container->get('request_stack')->getCurrentRequest();

        $form = $this->createFormBuilder()
                ->add('attachment', FileType::class, array('label' => 'Выберите XML файл для импорта','attr'=>array('accept'=>"text/xml,application/xml")))
                ->add('save', SubmitType::class, array('label' => 'Импортировать','attr'=>array('class'=>'add')))
                ->getForm();

        $form->handleRequest($request);  
        if ($form->isSubmitted()) { 
            if ($form->isValid()) {
                $file=$form['attachment']->getData();
                
                $crawler = new Crawler();
                $crawler->addXmlContent(file_get_contents($file));
                $orgs = $crawler->filter('orgs > org');
                
                if(count($orgs)>0){
                    foreach ($orgs as $org) {
                        $Organization = $this->getDoctrine()->getRepository('SiteTestBundle:Organizations')->findOneByOktmo($org->getAttribute('oktmo'));
                        if(!$Organization){
                            $orgid=$this->AddOrganization($org);
                            if($orgid){
                                $this->countAddOrganizations++;
                                $Organization = $this->getDoctrine()->getRepository('SiteTestBundle:Organizations')->findOneById($orgid);
                                $this->AddUsersNode($org,$Organization);
                            }
                        }else{
                            if($this->AddUsersNode($org,$Organization)){$this->countAddUsers++;}
                        }
                    }
                    if($this->countAddUsers>0){
                        $this->get('session')->getFlashBag()->add('alert', "Добавленно $this->countAddUsers новых пользователей!");   
                    }
                    if($this->countAddOrganizations>0){
                        $this->get('session')->getFlashBag()->add('alert', "Добавленно $this->countAddOrganizations новых организаций!");   
                    }
                }  else {
                    $this->get('session')->getFlashBag()->add('error', 'Неверный формат данных!');
                }
            }
        } 
    
    return $this->render('SiteTestBundle:XmlLoader:Load.html.twig',  array('form' => $form->createView()));      
    }
    
    /*
     * Add new Organization
     */
    public function AddOrganization($org)
    {
        $OrgName=$org->getAttribute('displayName');
        $flashbag=$this->get('session')->getFlashBag();
        
        $Organization = new Organizations();
        $Organization->setDisplayName($OrgName);
        $Organization->setOgrn($org->getAttribute('ogrn'));
        $Organization->setOktmo($org->getAttribute('oktmo'));
        
        $form = $this->createForm(OrganizationsType::class, $Organization);
        $validator = $this->get('validator');
        $errors = $validator->validate($form);
        

        foreach ($errors as $error){
            $flashbag->add('notice', '"'.$OrgName.'": '.$error->getMessage());
        }
        if (count($errors)==0) {
            return $this->SaveDb($Organization);
        }else{
            return false;
        }
        
    }
    
    /*
     * Function passing on user list
     */
    public function AddUsersNode($org,$Organization)
    {
        $users = $org->childNodes;
        foreach ($users as $user) {
            if($user->hasAttributes()){
                $this->AddUser($user,$Organization);
            }
        } 
    }
    
    /*
     * Add New User
     */
    public function AddUser($user,$Organization)
    {
        $UserName=$user->getAttribute('lastname').' '.$user->getAttribute('firstname');
        $flashbag=$this->get('session')->getFlashBag();
        
        $NewUser = new Users();
        $NewUser->setFirstname($user->getAttribute('firstname'));
        $NewUser->setLastname($user->getAttribute('lastname'));
        $NewUser->setMiddlename($user->getAttribute('middlename'));
        $NewUser->setOrgid($Organization);
        $NewUser->setSnils($user->getAttribute('snils'));
        $NewUser->setInn($user->getAttribute('inn'));
        $NewUser->setDatebirth(($user->getAttribute('datebirth')!='')?$user->getAttribute('datebirth'):new \DateTime('1900-01-01'));
        
        
        $form = $this->createForm(UsersType::class, $NewUser);
        $validator = $this->get('validator');
        $errors = $validator->validate($form);
        

        foreach ($errors as $error){
            $flashbag->add('notice', '"'.$UserName.'": '.$error->getMessage());
        }
        if (count($errors)==0) {
            $this->countAddUsers++;
            return $this->SaveDb($NewUser);
        }else{
            return false;
        }

    }

    
    /*
     * Function save data to entity
     */
    public function SaveDb($data){
        $em = $this->getDoctrine()->getEntityManager();
        try {
                $em->persist($data);
                $em->flush(); 
                return $data->getId();
            }catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', 'Ошибка: '. $e->getMessage());
            } 
    }
    
}
