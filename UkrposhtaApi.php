<?php
/**
 * Created by Eugene.
 * User: eugene
 * Date: 19/03/18
 * Time: 09:58
 */

/**
 * Ukrposhta API class
 *
 * @author mrdonald245
 * @method mixed addresses(...$arguments)
 * @method mixed clients(int $client_uuid = null)
 */
class UkrposhtaApi
{
    /**
     * @var string URL url where all of the requests go
     */
    const URL = 'https://www.ukrposhta.ua/';

    /**
     * @var string APP_NAME version of the API,
     * goes just after URL
     */
    const APP_NAME = 'ecom/0.0.1/';

    /**
     * @var array ROUTES where key is a UkrposhtaApi method name
     * and values are its requested method URLs.
     */
    const ROUTES = [
        'addresses' => [
            'post' => 'addresses',
            'get' => 'addresses/{id}'
        ],
        'clients' => [
            'post' => 'clients?token={token}',
            'get' => 'clients/{client_uuid}?token={token}',
            'put' => 'clients/{client_uuid}?token={token}',
            'delete' => 'clients/{client_uuid}?token={token}'
        ]
    ];

    /**
     * @var string $token API token
     */
    private $token;

    /**
     * @var string $bearer authorization bearer
     */
    private $bearer;

    /**
     * @var string $method may be either POST, GET, PUT or DELETE
     */
    private $method = 'POST';

    /**
     * @var string $route API request URL
     */
    private $route;

    /**
     * @var array $params params of current method
     */
    private $params;


    /**
     * UkrposhtaApi constructor.
     *
     * @param string $bearer
     * @param string $token
     */
    public function __construct($bearer, $token)
    {
        $this->bearer = $bearer;
        $this->token = $token;
    }

    /**
     * Set method and empties params properties
     *
     * @param string $method by default it is POST
     * @return $this
     */
    public function method($method = 'POST')
    {
        $this->method = $method;
        $this->route = null;
        $this->params = null;
        return $this;
    }

    /**
     * Set route of current method and clear params;
     *
     * @param string $route request url to API
     * @return $this
     */
    public function route($route)
    {
        $this->route = $route;
        $this->params = null;
        return $this;
    }

    /**
     * Set params of current route
     *
     * @param array $params
     * @return $this
     */
    public function params($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Call API methods
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     * @throws BadMethodCallException if there is no such method or request is invalid
     * @throws Exception with curl error message
     *
     * @return array
     */
    public function __call($name, $arguments)
    {
        $routes = self::ROUTES;

        // Checks if there is such route
        if (isset($routes[$name])) {
            $route = $routes[$name];
            $method = strtolower($this->method);

            if (isset($route[$method])) {
                $url = $route[$method];
                $this->route = $this->substituteUrlWithData($url, $arguments);

                return $this->execute();

            } else {
                throw new BadMethodCallException(
                    "Requested method $this->method is unavailable in Nova Poshta API");
            }
        } else {
            throw new BadMethodCallException("There is no such method as $name");
        }
    }

    /**
     * Execute request to UkrPoshta API
     *
     * @return array
     * @throws Exception with curl error message
     */
    public function execute()
    {
        $json = $this->request($this->method, $this->route, $this->params);
        return json_decode($json, true);
    }

    /**
     * Make a request to the API.
     *
     * @param string $method
     * @param string $route
     * @param array|null $params required params
     *
     * @return mixed
     * @throws Exception with curl error message
     */
    private function request($method, $route, $params = null)
    {
        $full_url = $this->getFullUrl($route);
        $ch = curl_init($full_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: Bearer $this->bearer",
        ]);

        if ($params != null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

        return $result;
    }

    /**
     * Exchange request API URL with data
     *
     * @example  /addresses/{id} id - will be replaced with address
     * @param string $template_url
     * @param array $params
     * @throws InvalidArgumentException if user passed wrong quantity of arguments
     *
     * @return string url with all substituted params
     */
    private function substituteUrlWithData($template_url, $params)
    {
        $pattern = '/(?!{token}){[\w]+}/'; // {id}, {uu_id} ...
        $params_count = count($params);
        $url = '';

        // Checks if params quantity are valid
        $tpl_params_count = preg_match_all($pattern, $template_url);
        if ($tpl_params_count != $params_count) {
            throw new InvalidArgumentException(
                "A method needs $tpl_params_count parameter(s), you passed $params_count parameter(s)");
        }

        // Replace {token} with current token if necessary
        $url = str_replace('{token}', $this->token, $template_url);

        if (!$params_count) {
            return $template_url;
        }

        // Replace {blocks} with $params
        $url = preg_replace_callback($pattern, function () use (&$params) {
            return array_shift($params);
        }, $url);

        return $url;
    }

    /**
     * Concatenates URL with APP_NAME
     *
     * @param string $route_url 'addresses' for example
     * @return string
     */
    private function getFullUrl($route_url) { return self::URL . self::APP_NAME . $route_url; }
}