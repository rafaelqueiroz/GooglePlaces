<?php
App::uses('Component', 'Controller');
App::uses('HttpSocket', 'Network/Http');
/**
 * Google Places Component
 *
 * @author Rafael F Queiroz <rafaelfqf@gmail.com>
 */
class GooglePlacesComponent extends Component {

	/**
	 * Key
	 *
	 * @var string
	 */
	protected $key = null;

	/**
	 * Url
	 *
	 * @var string
	 */
	protected $url = "https://maps.googleapis.com/maps/api/place";

	/**
	 * Output
	 * 
	 * @var string
	 */
	protected $output = 'json';

	/**
	 * Constructor
	 *
	 * @param ComponentCollection $collection
	 * @param array $settings
	 * @return GooglePlacesComponent
	 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
		$this->settings = array_merge($this->settings, $settings);
	}

	/**
	 * Initialize callback
	 *
	 * @param Controller $controller
	 * @param array $settings
	 * @return void
	 */
	public function initialize(Controller $controller, $settings = array()) {
		$this->key = Configure::read('GooglePlaces.key');
		if (!$this->key) {
			throw new CakeException ("You must set GooglePlaces.key configuration");
		}
		$this->Http = new HttpSocket();
	}

	/**
	 * Autocomplete
	 * Returns place predictions in response
	 *
	 * @param string $input
	 * @param array  $params
	 * @return
	 */
	public function autocomplete($input, $params = array()) {
		
		$url = "{$this->url}/autocomplete/{$this->output}";
		$params  = array_merge(array('input' => $input, 'key' => $this->key), $params);
		$request = $this->_makeRequest($url, $params);

		if ($request->isOk()) {
			$response = json_decode($request->body);
			return $response;
		}

		return false;
	}

	/**
	 * Make a Request.
	 *
	 * @var string $name
	 * @var array $params
	 * @return HttpSocket
	 */
	protected function _makeRequest($url, $params = array()) {
		return $this->Http->get($url, http_build_query($params));
	}

}