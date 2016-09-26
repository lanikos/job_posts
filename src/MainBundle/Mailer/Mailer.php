<?php

namespace MainBundle\Mailer;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use MainBundle\Entity\JobPosts;

/**
 * Mailer service
 *
 * @author Nikola Steric
 */
class Mailer {
    
    protected $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }
    
    public function sendMailToModerator(JobPosts $jobPost) {
        $headers = array(
          "From: jobs@piratetech.rs",
          "Content-Type: text/html"
        );
        
        $publishUrl = $this->router->generate('publish', array('id' => $jobPost->getId()), true);
        $spamUrl = $this->router->generate('spam', array('id' => $jobPost->getId()), true);
        
        $subject = 'New job post - '.$jobPost->getTitle();
        
        $body = '<html>' .
            '<head></head>' .
            '<body>' .
            '<p><b>Job title:</b></p>' . $jobPost->getTitle() .
            '<p><b>Description:</b></p>' . $jobPost->getDescription() .
            '<p><a href="' . $publishUrl . '" style="color: blue">Publish</a></p>' .
            '<p><a href="' . $spamUrl . '" style="color: red">Mark as spam</a></p>' .    
            '</body>' .
            '</html>';

        
        mail('root@localhost', $subject, $body, implode("\r\n", $headers));
        
    }
    
    
}
