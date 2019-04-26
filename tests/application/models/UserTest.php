<?php
/**
 * Description of UserTest
 *
 * @author jon
 */
class Model_UserTest
    extends PHPUNit_Framework_TestCase
{
    private $username = 'jon';
    private $password = 'abc123';

    public function testUserModel()
    {
        $u = new Model_User();
        $this->assertType("Model_User",$u);
    }

    public function testCanCreateUsers()
    {
        Model_User::create($this->username, $this->password);
        $users = Model_User::getUsers();
        $this->assertArrayHasKey($this->username, $users);

        $user = new Model_User();
        $user->username = 'myuser';
        $user->password = 'mypass';
        $user->save();
        $users = Model_User::getUsers();
        $this->assertArrayHasKey('myuser', $users);

    }
    public function testCanFindByUsername()
    {
        Model_User::create($this->username, $this->password);
        $user = Model_User::findByUsername($this->username);
        $this->assertType('Model_User', $user);

        $userNotFound = Model_User::findByUsername('asdasdasd');
        $this->assertFalse($userNotFound);
        
    }

    public function testCanDeleteUsername()
    {
        Model_User::create($this->username, $this->password);
        $user = Model_User::findByUsername($this->username);
        $user->delete();

        $userNotFound = Model_User::findByUsername($this->username);
        $this->assertFalse($userNotFound);
  
        
    }    
}

