<?php

namespace MySecurityBundle\Security;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class PasswordEncoder extends MessageDigestPasswordEncoder {

    private $raw;
    private $encoderRaw;

    public function encodePassword($raw, $salt) {
        $this->raw = $raw;
        
        if (!$this->raw)
        {
            throw new \InvalidArgumentException('Cannot use empty raw.');
        }

        $this->encoderRaw = $raw;

        return $this->encoderRaw;
    }

}
