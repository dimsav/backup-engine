<?php namespace Dimsav\Backup\Storage;

interface PasswordProtectedStorage {

    public function getUsername();
    public function getPassword();

} 