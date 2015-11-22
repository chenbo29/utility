<?php

namespace Cake\Network;

use Cake\Network\Response;

class CorsBuilder
{
    protected $_response;
    protected $_origin;
    protected $_isSsl;
    protected $_headers = [];

    public function __construct(Response $response, $origin, $isSsl = false)
    {
        $this->_origin = $origin;
        $this->_isSsl = $isSsl;
        $this->_response = $response;
    }

    public function build()
    {
        if (empty($this->_origin)) {
            return $this->_response;
        }
        if (isset($this->_headers['Access-Control-Allow-Origin'])) {
            $this->_response->header($this->_headers);
        }
        return $this->_response;
    }

    public function allowOrigin($domain)
    {
        $allowed = $this->_normalizeDomains((array)$domain);
        foreach ($allowed as $domain) {
            if (!preg_match($domain['preg'], $this->_origin)) {
                continue;
            }
            $value = $domain['original'] === '*' ? '*' : $this->_origin;
            $this->_headers['Access-Control-Allow-Origin'] = $value;
            break;
        }
        return $this;
    }

    /**
     * Normalize the origin to regular expressions and put in an array format
     *
     * @param array $domains Domain names to normalize.
     * @return array
     */
    protected function _normalizeDomains($domains)
    {
        $result = [];
        foreach ($domains as $domain) {
            if ($domain === '*') {
                $result[] = ['preg' => '@.@', 'original' => '*'];
                continue;
            }

            $original = $preg = $domain;
            if (strpos($domain, '://') === false) {
                $preg = ($this->_isSsl ? 'https://' : 'http://') . $domain;
            }
            $preg = '@' . str_replace('*', '.*', $domain) . '@';
            $result[] = compact('original', 'preg');
        }
        return $result;
    }

    public function allowMethods(array $methods)
    {
        $this->_headers['Access-Control-Allow-Methods'] = implode(', ', $methods);
        return $this;
    }

    public function allowCredentials()
    {
        $this->_headers['Access-Control-Allow-Credentials'] = 'true';
        return $this;
    }

    public function allowHeaders(array $headers)
    {
        $this->_headers['Access-Control-Allow-Headers'] = implode(', ', $headers);
        return $this;
    }

    public function exposeHeaders(array $headers)
    {
        $this->_headers['Access-Control-Expose-Headers'] = implode(', ', $headers);
        return $this;
    }

    public function maxAge($age)
    {
        $this->_headers['Access-Control-Max-Age'] = $age;
        return $this;
    }
}
