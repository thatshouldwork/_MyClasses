<?php

// Global Accounting Class to handle Account Management (Register, Login, Password Recovery)

class Accounting {


    public function __construct()
    {

    }



    // REGISTRATION

    /**
     *
     */
    protected function loginNameAlreadyExists(&$ref_dbConnection, &$ref_sqlQuery)
    {
        $loginNameCheckResult = false;

        $sqlResult = $ref_dbConnection->sendSqlQuery($ref_sqlQuery);

        if($sqlResult->num_rows > 0){
            $loginNameCheckResult = true;
        }

        return $loginNameCheckResult;
    }

    protected function registerUser(&$ref_dbConnection, &$ref_sqlQuery)
    {
        $userRegistrationResult = false;

        if($ref_dbConnection->sendSqlQuery($ref_sqlQuery))
        {
            $userRegistrationResult = true;
        }

        return $userRegistrationResult;
    }

    protected function createAccountActivationToken(&$ref_reg_email)
    {
        $now = time();

        return base64_encode( md5($ref_reg_email).md5($now) );
    }

    protected function createPasswordSalt()
    {
        $salt = base64_encode( $this->rand_chars("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890", 32) );

        return $salt;
    }

    private function rand_chars($c, $l, $u = FALSE)
    {
        if (!$u) for ($s = '', $i = 0, $z = strlen($c)-1; $i < $l; $x = rand(0,$z), $s .= $c{$x}, $i++);
        else for ($i = 0, $z = strlen($c)-1, $s = $c{rand(0,$z)}, $i = 1; $i != $l; $x = rand(0,$z), $s .= $c{$x}, $s = ($s{$i} == $s{$i-1} ? substr($s,0,-1) : $s), $i=strlen($s));
        return $s;
    }

    /**
     * Hashes a Password String and returns the String
     *
     * @param $ref_password
     * @return string
     */
    protected function createPasswordHash(&$ref_password)
    {
        return password_hash($ref_password, PASSWORD_DEFAULT);
    }

    /**
     *
     */
    protected function sendHTMLRegistrationMail(&$ref_reciever, &$ref_subject, &$ref_message, &$ref_sender)
    {
        // Header Information for HTML Mail
        $header_content     = array();
        $header_content[]   = "MIME-Version: 1.0";
        $header_content[]   = "Content-type: text/plain; charset=iso-8859-1";
        $header_content[]   = "From: ". $ref_sender ." <". $ref_sender .">";
        //$header_content[]   = "Bcc: JJ Chong <bcc@domain2.com>";
        //$header_content[]   = "Reply-To: Recipient Name <receiver@domain3.com>";
        $header_content[]   = "Subject: {". $ref_subject ."}";
        $header_content[]   = "X-Mailer: PHP/".phpversion();

        $header = implode("\r\n", $header_content);

        if(mail($ref_reciever, $ref_subject, $ref_message, $header))
            return true;
        else
            return false;
    }

    /**
     *
     */
    protected function sendTextRegistrationMail(&$ref_reciever, &$ref_subject, &$ref_message, &$ref_sender)
    {
        if(mail($ref_reciever, $ref_subject, $ref_message, $ref_sender))
            return true;
        else
            return false;
    }

    protected function accountActivation()
    {

    }

    protected function sendHTMLPasswordRecoverMail()
    {

    }

    protected function sendTextPasswordRecoverMail()
    {

    }




    // AUTHENTICATION

    /**
     * Checks the Login Data from the Website with the Database
     *
     * @param
     * @return void
     */
    protected function performLogin(&$ref_loginName, &$ref_loginPassword, &$ref_loginPersistent, &$ref_sqlQuery, &$ref_sessionLoginCheck)
    {
        $loginAttemptResult = false;

        $sqlResult = $ref_dbConnection->sendSqlQuery($ref_sqlQuery);

        if($sqlResult->num_rows > 0){
            $loginAttemptResult = true;
        }


        $this->checkLoginData();

        return $loginAttemptResult;
    }


    /**
     *
     */
    protected function performLogout()
    {
        /*
         * if exists
         * setcookie('rememberLogin', '', -1);
	     * setcookie('rememberLoginToken', '', -1);
         */

        session_unset();
        session_destroy();
        //$_SESSION = array();
    }

    protected function checkPassword(&$ref_userPasswordHash, &$ref_dbPasswordHash)
    {
        return password_verify($ref_userPasswordHash, $ref_dbPasswordHash);
    }


    // Private Functions, only used in this Class

    /**
     *
     */
    private function checkLoginData()
    {
        $_SESSION[$this->sessionLoginCheck] = 1; // ändern in User ID (verschlüsselt)
    }

    /**
     * @return User Data from Database
     */
    protected function getUserData(&$ref_sqlQuery)
    {
        $sqlResult = $ref_dbConnection->sendSqlQuery($ref_sqlQuery);

        return $sqlResult;
    }

    /**
     *
     */
    private function getUserHash(&$ref_hashPepper, &$ref_userData)
    {
        $pepper = $ref_hashPepper;

        $userHash = substr($ref_userData['username'],0,3)."|".substr($ref_userData['userpw'],0,3)."|".substr($ref_userData['usermail'],0,3)."|".$pepper;

        return $userHash;
    }

    /**
     * Creates a Cookie for Auto Login
     */
    private function createAutoLoginCookie()
    {
        $userId = base64_encode($ref_userId);
        $time = time() + 3600*24*7*52; // Haltbarkeit 52 Wochen ~ 1 Jahr
        setcookie('rememberLogin', $userId, $time);

        $userDataHash = $this->getUserHash();

        return;
    }

    /**
     *  Updates the Timestamp in the Database for the Last Login
     *
     */
    private function updateLastLogin()
    {
        return;
    }




    // PASSWORD RECOVERY

    

}