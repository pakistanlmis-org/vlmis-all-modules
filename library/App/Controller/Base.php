<?php

class App_Controller_Base extends Zend_Controller_Action {

    protected $_identity;
    protected $_userid;
    protected $_user_level;
    protected $_em;

    /**
     * for read only connection
     * @var type 
     */
    protected $_em_read;
    protected $_local;
    protected $_session;
    private $_lang = 'en';
    public $translate;

    public function init() {
        $this->_identity = App_Auth::getInstance();
        if ($this->_identity->hasIdentity()) {
            $this->_userid = $this->_identity->getIdentity();
            $this->_user_level = $this->_identity->getUserLevel($this->_userid);
        }
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');

        $this->_initSession();

        if (isset($this->_request->lang) && !empty($this->_request->lang)) {
            $this->_session->lang = $this->_request->lang;
        }

        $this->_initLocale();
        $this->_initTranslation();
    }

    /**
     * create instance of Zend_Session_Namespace
     */
    protected function _initSession() {
        $this->_session = new Zend_Session_Namespace('vlmis');
    }

    protected function _initLocale() {
        if (isset($this->_session->lang) && !empty($this->_session->lang)) {
            $locale = new Zend_Locale($this->_session->lang);
        } else {
            $locale = new Zend_Locale($this->_lang);
        }

        $this->_locale = $locale;
    }

    protected function _initTranslation() {
        // Get Locale
        $locale = $this->_locale;

        // Set up and load the translations (there are my custom translations for my app)
        $translate = new Zend_Translate(
                array(
            'adapter' => 'ini',
            //'content' => APPLICATION_PATH . '/languages/' . $locale . '.ini',
            //'locale' => $locale,
            'content' => APPLICATION_PATH . '/languages/en.ini',
            'locale' => 'en')
        );

        Zend_Registry::set('Zend_Translate', $translate);

        $this->translate = $translate;
    }

}
