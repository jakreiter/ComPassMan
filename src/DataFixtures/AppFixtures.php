<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Role;
use App\Entity\FieldType;
use App\Entity\SurveyForm;
use App\Entity\FormField;
use App\Entity\FormFieldChoice;
use App\Entity\AccessEntryCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements ContainerAwareInterface
{

	/**
	 *
	 * @var ContainerInterface
	 */
	private $container;

	private $encoder;

	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}

	public function load(ObjectManager $manager)
	{
		$connection = $manager->getConnection();

		$connection->exec("ALTER TABLE role AUTO_INCREMENT = 10;");
		$connection->exec("ALTER TABLE user AUTO_INCREMENT = 100;");
		$connection->exec("ALTER TABLE user_roles AUTO_INCREMENT = 1000;");
        $connection->exec("ALTER TABLE access_entry_category AUTO_INCREMENT = 20;");

        $accessEntryCategory = new AccessEntryCategory();
        $accessEntryCategory->setTitle('Servers');
        $manager->persist($accessEntryCategory);

        $accessEntryCategory = new AccessEntryCategory();
        $accessEntryCategory->setTitle('Domain Accounts');
        $manager->persist($accessEntryCategory);

        $manager->flush();
		
		$ordCounter = 10;		
		
		$roleUser = new Role();
		$roleUser->setName('ROLE_USER');
		$manager->persist($roleUser);
		
		$role1 = new Role();
		$role1->setName('ROLE_HELPDESK');
		$manager->persist($role1);
		
		$role1 = new Role();
		$role1->setName('ROLE_ITOPS');
		$manager->persist($role1);
		
		$role1 = new Role();
		$role1->setName('ROLE_MANAGER');
		$manager->persist($role1);
		
		$roleAdmin = new Role();
		$roleAdmin->setName('ROLE_ADMIN');
		$manager->persist($roleAdmin);
		
		$user = new User();
		$email = $this->container->getParameter('app.admin_email_initial');
        $plainPassword = $this->container->getParameter('app.admin_pass_initial');
		$user->setEmail($email);
		$user->setUsername('admin');
		$user->addRolesAssigned($roleUser);
		$user->addRolesAssigned($roleAdmin);
        $masterKey = $user->generateMasterKey();

        echo "masterKey:    \n".bin2hex($masterKey);
        echo "\n---------------\n";
        $key = $user->convertPasswordIntoUserKey($plainPassword);
        echo ("paskey:  \n".$key);
        echo "\n";
        $encryptedMasterKey = $user->encrypt($masterKey, $key);
        echo "\n---encryptedMasterKey:\n$encryptedMasterKey";
        echo "\n";
        $user->setEncryptedMasterKey($encryptedMasterKey);


		// $encoder = new UserPasswordEncoderInterface();

		$encoded = $this->encoder->encodePassword($user, $plainPassword);
		
		// $hash = $this->getContainer()->get('security.password_encoder')->encodePassword($user, 'user password');
		$user->setPassword($encoded);
		$manager->persist($user);

        // Next user

        $user = new User();
        $email = $this->container->getParameter('app.admin_email_initial').'2';
        $plainPassword = $this->container->getParameter('app.admin_pass_initial').'2';
        $user->setEmail($email);
        $user->setUsername('admin2');
        $user->addRolesAssigned($roleUser);
        $key = $user->convertPasswordIntoUserKey($plainPassword);
        $encryptedMasterKey = $user->encrypt($masterKey, $key);
        $user->setEncryptedMasterKey($encryptedMasterKey);
        $encoded = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encoded);
        $manager->persist($user);

		$manager->flush();

	}
}
