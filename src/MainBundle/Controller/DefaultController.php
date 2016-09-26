<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use MainBundle\Entity\JobPosts;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $jobs = $em->getRepository('MainBundle:JobPosts')->findBy(array('status' => 1));
        
        return $this->render('MainBundle:Default:index.html.twig', array(
            'jobs' => $jobs
        ));
    }
    
    /**
     * Creates new job post.
     *
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($request->isMethod('POST')) {
            $jobPost = new JobPosts();
            $jobPost->setTitle($request->get('title'));
            $jobPost->setDescription($request->get('description'));
            $jobPost->setMail($request->get('mail'));
            
            $jobs = $em->getRepository('MainBundle:JobPosts')->findBy(array('mail' => $request->get('mail')));
            if (!empty($jobs)) {
                $jobPost->setStatus(1);
                $alert = array(
                    'class' => 'success',
                    'message' => 'Your job submission is successfully published!'
                );
            } else {
                $jobPost->setStatus(0);
                $alert = array(
                    'class' => 'warning',
                    'message' => 'Your job submission is in moderation!'
                );
            }
           
            // save new job to database
            $em->persist($jobPost);
            $em->flush();
            
            // send mail to moderator
            $this->get('mailer_service')->sendMailToModerator($jobPost);
            
            // generate response
            $engine = $this->container->get('templating');
            $content = $engine->render('MainBundle:Default:show.html.twig', array(
                'job' => $jobPost,
                'alert' => $alert
            ));

            $response = array(
                'content' => $content
            );

            return new JsonResponse($response);
        }
        
        return $this->render('MainBundle:Default:new.html.twig');
    }
    
    /**
     * Publish new job
     *
     * @Route("/publish/{id}", name="publish")
     * @Method("GET")
     */
    public function publishAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $newJob = $em->getRepository('MainBundle:JobPosts')->find($id);
        $newJob->setStatus(1);
        
        $em->persist($newJob);
        $em->flush();
        
        // generate response
        $alert = array(
            'class' => 'success',
            'message' => 'Job is successfully published!'
        );
        
        return $this->render('MainBundle:Default:info.html.twig', array(
            'job' => $newJob,
            'alert' => $alert
        ));
    }
    
    /**
     * Mark new job as spam
     *
     * @Route("/spam/{id}", name="spam")
     * @Method("GET")
     */
    public function spamAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $newJob = $em->getRepository('MainBundle:JobPosts')->find($id);
        $newJob->setStatus(2);
        
        $em->persist($newJob);
        $em->flush();
        
        // generate response
        $alert = array(
            'class' => 'danger',
            'message' => 'Job is marked as spam!'
        );
        
        return $this->render('MainBundle:Default:info.html.twig', array(
            'job' => $newJob,
            'alert' => $alert
        ));
    }
    
    /**
     * @Route("/moderator", name="moderator")
     */
    public function moderatorAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $jobs = $em->getRepository('MainBundle:JobPosts')->findAll();
        
        return $this->render('MainBundle:Default:moderator.html.twig', array(
            'jobs' => $jobs
        ));
    }
    
    /**
     * Redirect
     *
     * @Route("/admin", name="admin")
     */
    public function adminAction() {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $role = $user->getRoles();
        
        if ($role[0] == "ROLE_MANAGER") {
            return $this->redirect($this->generateUrl('new'));
        }
        
        if ($role[0] == "ROLE_MODERATOR") {
            return $this->redirect($this->generateUrl('moderator'));
        }
    }
}
