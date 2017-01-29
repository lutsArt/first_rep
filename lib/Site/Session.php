<?php

namespace Site;

class Session {
	public $routeMap = null;

	protected $messages = array();

	const DEFAULT_SAVE_PATH       = '/tmp';
	const DEFAULT_EXPIRATION_TIME = 3600;
	const SESSION_NAME            = 'CGISID';
	const MESSAGES_KEY            = 'flash.messages';

	private $_savePath = self::DEFAULT_SAVE_PATH;
	private $_expire   = self::DEFAULT_EXPIRATION_TIME;

	public function __construct($options = array()) {
		/* Destroy any existing sessions */
		if ( session_id() ) {
			session_unset();
			session_destroy();
		}

		$this->_setOptions($options);
		$this->_setCookieParams();

		if ( !session_start() ) {
			throw new \Exception("Can't to create a new session");
		}

		if ( isset($_COOKIE[self::SESSION_NAME]) ) {
			setcookie(self::SESSION_NAME, $_COOKIE[self::SESSION_NAME], time() + $this->_expire, "/");
		}

		$this->messages = array(
			'prev' => array(),
			'next' => array(),
			'now' => array(),
		);
		$this->loadMessages();
	}

	private function _setOptions($options) {
		if ( isset($options['savePath']) ) {
			$this->_savePath = $options['savePath'];
		}

		if ( isset($options['expire']) ) {
			$this->_expire = $options['expire'];
		}

		ini_set('session.name', self::SESSION_NAME);

		/* Disable transparent sid support */
		ini_set('session.use_trans_sid', '0');

		/* Only allow the session ID to come from cookies and nothing else. */
		ini_set('session.use_only_cookies', '1');
		
		if(ini_get('session.save_handler') == 'files'){
			ini_set('session.save_path', $this->_savePath);
		}

		ini_set('session.gc_maxlifetime', $this->_expire);
	}

	private function _setCookieParams() {
		$cookie = session_get_cookie_params();

		$cookie['path']     = '/';
		$cookie['lifetime'] = $this->_expire;

		session_set_cookie_params($cookie['lifetime'], $cookie['path'], $cookie['domain'], $cookie['secure'], true);
	}

	public function getId() {
		return session_id();
	}

	public function getExpire() {
		return $this->_expire;
	}

	public function setExpire($expire) {
		$this->_expire = $expire;
		ini_set('session.gc_maxlifetime', $this->_expire);
	}

	public function get($name) {
		return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
	}

	public function set($name, $value) {
		$_SESSION[$name] = $value;
	}

	public function close() {
		$this->saveMessages();
		session_write_close();
	}

	public function __destruct() {
		$this->close();
	}

	public function loadMessages() {
		if (isset($_SESSION[self::MESSAGES_KEY])) {
			$this->messages['prev'] = $_SESSION[self::MESSAGES_KEY];
		}
	}

	public function getMessages() {
		return array_merge($this->messages['prev'], $this->messages['now']);
	}

	public function saveMessages() {
		$_SESSION[self::MESSAGES_KEY] = $this->messages['next'];
	}

	public function setFlash($key, $value) {
		$this->messages['next'][(string)$key] = $value;
	}

	public function getFlash($key, $unset = true) {
		$messages = $this->getMessages();
		if (isset($messages[$key])) {
			if ($unset) {
				$this->unsetFlash($key);
			}
			return $messages[$key];
		}
		return null;
	}

	public function hasFlash($key) {
		$messages = $this->getMessages();
		return isset($messages[$key]);
	}

	public function unsetFlash($key) {
		unset($this->messages['prev'][$key], $this->messages['now'][$key]);
	}
}

?>
