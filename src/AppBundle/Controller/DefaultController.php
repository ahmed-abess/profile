<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/{_locale}/users",name="get_users")
     */
    public function apiAction(Request $request)
    {
        $i=1;
        $usr= array();
        $users= array();

        do{
            $api = file_get_contents('https://reqres.in/api/users?page='.$i);
            $page=json_decode($api);

            array_push($usr,$page->data);
            $i++;
        }while($page->data);

        foreach ($usr as $item ){
            foreach ($item as $value ){
                array_push($users,$value);

            }

        }


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $users, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );


        return $this->render('default/users.html.twig',array('users'=>$pagination) );


    }
    /**
     * @Route("/{_locale}/profile/{id}",name="profile")
     */
    public function profileAction(Request $request,$id){

        $api = json_decode(file_get_contents('https://reqres.in/api/users/'.$id));
        $user=$api->data;

        return $this->render('default/user.html.twig', array('user'=>$user));



    }
}
