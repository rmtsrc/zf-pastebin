<?php
// application/models/Pastebin.php

class Default_Model_Pastebin
{
    protected $_code;
    protected $_language;
    protected $_created;
    protected $_ipAddress;
    protected $_expires;
    protected $_name;
    protected $_shortId;
    protected $_id;
    protected $_mapper;

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid pastebin property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid pastebin property');
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function setCode($text)
    {
        $this->_code = (string) $text;
        return $this;
    }

    public function getCode()
    {
        return $this->_code;
    }

    public function setLanguage($text)
    {
        $this->_language = (string) $text;
        return $this;
    }

    public function getLanguage()
    {
        return $this->_language;
    }

    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setCreated($ts)
    {
        $this->_created = $ts;
        return $this;
    }

    public function getCreated()
    {
        return $this->_created;
    }

    public function setIpAddress($ip)
    {
        $this->_ipAddress = $ip;
        return $this;
    }

    public function getIpAddress()
    {
        return $this->_ipAddress;
    }

    public function setExpires($ts)
    {
        $this->_expires = $ts;
        return $this;
    }

    public function getExpires()
    {
        return $this->_expires;
    }

    public function setShortId($shortId)
    {
        $this->_shortId = $shortId;
        return $this;
    }

    public function getShortId()
    {
        return $this->_shortId;
    }

    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_PastebinMapper());
        }
        return $this->_mapper;
    }

    public function save()
    {
        return $this->getMapper()->save($this);
    }

    public function find($id)
    {
        $this->getMapper()->find($id, $this);
        return $this;
    }

    public function findShortId($shortId)
    {
        $this->getMapper()->findShortId($shortId, $this);
        return $this;
    }

    public function fetchAll()
    {
        return $this->getMapper()->fetchAll();
    }

    public function delete($id, $column = 'id')
    {
        return $this->getMapper()->delete($id, $column);
    }

    public function getPastebin($id = null) {
        return $this->getMapper()->getPastebin($id);
    }

}
