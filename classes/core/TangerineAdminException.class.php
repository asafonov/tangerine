<?php

/**
 * Tangerine CMS admin panel Exception class
 */
class TangerineAdminException extends TangerineException {
    public function __construct($message = null, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code,$previous);
    }
}

?>