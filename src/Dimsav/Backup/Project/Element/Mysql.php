<?php namespace Dimsav\Backup\Project\Element;

class Mysql extends AbstractElement implements Element {

    private $name;
    private $host;
    private $port;
    private $username;
    private $password;

    public function fill($data)
    {
        $this->name = isset($data['name']) ? $data['name'] : $this->name;
        $this->host = isset($data['host']) ? $data['host'] : $this->host;
        $this->port = isset($data['port']) ? $data['port'] : $this->port;
        $this->username = isset($data['username']) ? $data['username'] : $this->username;
        $this->password = isset($data['password']) ? $data['password'] : $this->password;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

}
