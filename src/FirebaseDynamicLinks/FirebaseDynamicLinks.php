<?php namespace AgelxNash\FirebaseDynamicLinks;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class FirebaseDynamicLinks
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param array $config
     * @param ClientInterface|null $client
     * @throws Exceptions\ConfigException
     */
    public function __construct(array $config, ClientInterface $client = null)
    {
        if ($this->validateConfig($config)) {
            $this->config = $config;
        }

        $this->client = $client ?? new Client();
    }

    /**
     * @param string $link
     * @return string
     * @throws Exceptions\ResponseBodyException
     * @throws Exceptions\ResponseStatusException
     */
    public function shorten(string $link) : string
    {
        $client = $this->getClient();
        $response = $client->post($this->makeFirebaseLink(), [
            'json' => [
                'longDynamicLink' => $this->makeDynamicLink($link)
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exceptions\ResponseStatusException('Status code', $response->getStatusCode());
        }
        $content = $response->getBody()->getContents();
        $json = json_decode($content);

        if (! isset($json->shortLink)) {
            if (isset($json->error->message)) {
                $exception = new Exceptions\ResponseBodyException(
                    $json->error->message,
                    $json->error->code
                );
            } else {
                $exception = new Exceptions\ResponseBodyException();
            }
            throw $exception->setBody($content);
        }

        return $json->shortLink;
    }

    /**
     * @return string
     */
    public function makeFirebaseLink() : string
    {
        return 'https://firebasedynamiclinks.googleapis.com/v1/shortLinks?key=' . $this->get('api_key');
    }

    /**
     * @param string $link
     * @return string
     */
    public function makeDynamicLink(string $link) : string
    {
        return 'https://' . $this->get('app_id') . '.app.goo.gl?link=' . $link;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        return $this->config[$key] ?? null;
    }

    /**
     * @param array $config
     * @return bool
     * @throws Exceptions\ConfigException
     */
    protected function validateConfig(array $config) : bool
    {
        if (empty($config['api_key']) || ! is_scalar($config['api_key'])) {
            throw new Exceptions\ConfigException('api_key');
        }

        if (empty($config['app_id']) || ! is_scalar($config['app_id'])) {
            throw new Exceptions\ConfigException('app_id');
        }

        return true;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param ClientInterface $client
     */
    public function setClient(ClientInterface $client) : void
    {
        $this->client = $client;
    }
}
