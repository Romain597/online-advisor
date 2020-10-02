<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use App\Account;
use App\User;

class UserTest extends TestCase
{
    // Intern method

    private function initAccount()
    {
        $date = new \DateTime();
        return new Account( 'type' , 'name' , 'token' , 1 , $date , $date , null );
    }

    private function initUser()
    {
        return new User();
    }

    // Tested method : getAccount

    public function testGetAccountWithoutParameter()
    {
        $user = $this->initUser();
        $this->assertNull( $user->getAccount() );
    }

    public function testGetAccountWithGoodParameter()
    {
        $user = $this->initUser();
        $account = $this->initAccount();
        $user->attachAccount( $account );
        $userAccount = $user->getAccount();
        $this->assertInstanceOf( Account::class , $userAccount );
        $this->assertSame( $account , $userAccount );
    }

    // Tested method : hasAccount

    public function testHasAccountWithBadTypeParameter()
    {
        $user = $this->initUser();
        $this->assertFalse( $user->hasAccount('none') );
    }
    
    public function testHasAccountWithoutParameter()
    {
        $user = $this->initUser();
        $this->assertFalse( $user->hasAccount() );
    }

    public function testHasAccountWithGoodParameter()
    {
        $user = $this->initUser();
        $account = $this->initAccount();
        $user->attachAccount( $account );
        $this->assertTrue( $user->hasAccount() );
    }

    // Tested method : attachAccount
    
    public function testAttachAccountWithoutParameter()
    {
        $user = $this->initUser();
        $this->expectException(TypeError::class);
        $user->attachAccount();
    }
    
    public function testAttachAccountWithBadType()
    {
        $user = $this->initUser();
        $this->expectException(TypeError::class);
        $user->attachAccount( [] );
    }

    public function testAttachAccountWithGoodParameter()
    {
        $user = $this->initUser();
        $account = $this->initAccount();
        $user->attachAccount( $account );
        $this->assertSame( $account , $user->getAccount() );
    }

    // Tested method : checkLoginParameters

    public function testCheckLoginParametersWithoutParameters()
    {
        $user = $this->initUser();
        $this->expectException(TypeError::class);
        $user->checkLoginParameters();
    }

    public function testCheckLoginParametersWithBadTypeParameters()
    {
        $user = $this->initUser();
        $this->expectException(TypeError::class);
        $user->checkLoginParameters( 1 , '' );
    }

    public function testCheckLoginParametersWithNotAllowedParameters()
    {
        $user = $this->initUser();
        $this->expectException(InvalidArgumentException::class);
        $user->checkLoginParameters( '' , '' );
    }

    public function testCheckLoginParametersWithBadFormatParameters()
    {
        $user = $this->initUser();
        $this->assertFalse( $user->checkLoginParameters( 'id' , 'pass' ) );
    }

    public function testCheckLoginParametersWithGoodParameters()
    {
        $user = $this->initUser();
        $this->assertTrue( $user->checkLoginParameters( 'user@default.com' , 'password' ) );
    }

    // Tested method : checkCreateAccountParameters

    public function testCheckCreateAccountParametersWithoutParameters()
    {
        $user = $this->initUser();
        $this->expectException(TypeError::class);
        $user->checkCreateAccountParameters();
    }

    public function testCheckCreateAccountParametersWithBadTypeParameters()
    {
        $user = $this->initUser();
        $this->expectException(TypeError::class);
        $user->checkCreateAccountParameters( 1 , '' , [] );
    }

    public function testCheckCreateAccountParametersWithNotAllowedParameters()
    {
        $user = $this->initUser();
        $this->expectException(InvalidArgumentException::class);
        $user->checkCreateAccountParameters( '' , '' , 'n' );
    }

    public function testCheckCreateAccountParametersWithBadFormatParameters()
    {
        $user = $this->initUser();
        $this->assertFalse( $user->checkCreateAccountParameters( 'id' , 'pass' , 'name' ) );
    }

    public function testCheckCreateAccountParametersWithGoodParameters()
    {
        $user = $this->initUser();
        $this->assertTrue( $user->checkCreateAccountParameters( 'user@default.com' , 'password' , 'name' ) );
    }

}
