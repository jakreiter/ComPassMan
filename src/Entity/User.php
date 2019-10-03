<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

//use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    const ROLE_DEFAULT = 'ROLE_USER';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    private $newPassword;

    /**
     * @ORM\Column(type="string", length=127)
     * @var string
     * salt used to encrypt/dectrypt master key
     */
    protected $keySalt;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $encryptedMasterKey;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\JoinTable(name="user_roles")
     */
    protected $rolesAssigned;

    /**
     * @ORM\Column(type="string", length=127)
     * @var string
     * ciphr method and mode selection
     */
    protected $cipherMethod;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $masterKeyLength;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;


    public function __construct()
    {
        $this->isActive = true;
        $this->rolesAssigned = new ArrayCollection();
        $this->cipherMethod = 'aes-256-cbc';
        $this->masterKeyLength = 512;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
        $this->keySalt = bin2hex(openssl_random_pseudo_bytes(16));
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    public function generateMasterKey()
    {
        $iv_dec = openssl_random_pseudo_bytes($this->getMasterKeyLength());
        return $iv_dec;
    }

    public function convertPasswordIntoUserKey($plainPassword)
    {
        return hash('sha512', hex2bin($this->keySalt).$plainPassword);
    }

    /**
     * string decryption function
     * @param string $what
     * @param string $key
     * @return string
     * @author Jakub Reiter
     */
    public function encrypt($what, $key)
    {
        $iv_size = openssl_cipher_iv_length($this->getCipherMethod());
        $iv_dec = openssl_random_pseudo_bytes($iv_size);
        $ciphertext = openssl_encrypt($what, $this->getCipherMethod(), $key, OPENSSL_RAW_DATA, $iv_dec);
        $ciphertext = $iv_dec . $ciphertext;

        return bin2hex($ciphertext);
    }

    /**
     * string encryption function
     * @param string $what
     * @param string $key
     * @return string
     * @author Jakub Reiter
     */
    public function decrypt($what, string $key)
    {
        $iv_size = openssl_cipher_iv_length($this->getCipherMethod());

        $ciphertext_dec = hex2bin($what);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);

        return openssl_decrypt($ciphertext_dec, $this->getCipherMethod(), $key, OPENSSL_RAW_DATA, $iv_dec);
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        $roleCollection = $this->getRolesAssigned();
        $rArray = [];
        foreach ($roleCollection as $AssRole) {
            $rArray[] = $AssRole->getName();
        }
        return $rArray;
        //return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRolesAssigned(): Collection
    {
        return $this->rolesAssigned;
    }

    public function addRolesAssigned(Role $rolesAssigned): self
    {
        if (!$this->rolesAssigned->contains($rolesAssigned)) {
            $this->rolesAssigned[] = $rolesAssigned;
        }

        return $this;
    }

    public function removeRolesAssigned(Role $rolesAssigned): self
    {
        if ($this->rolesAssigned->contains($rolesAssigned)) {
            $this->rolesAssigned->removeElement($rolesAssigned);
        }

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getEncryptedMasterKey(): ?string
    {
        return $this->encryptedMasterKey;
    }

    public function setEncryptedMasterKey(?string $encryptedMasterKey): self
    {
        $this->encryptedMasterKey = $encryptedMasterKey;

        return $this;
    }

    public function getDecryptedMasterKey(string $key): ?string
    {
        $emc = $this->decrypt($this->encryptedMasterKey, $key);
        return $emc;
    }

    public function getCipherMethod(): ?string
    {
        return $this->cipherMethod;
    }

    public function setCipherMethod(string $cipherMethod): self
    {
        $this->cipherMethod = $cipherMethod;

        return $this;
    }

    public function getMasterKeyLength(): ?int
    {
        return $this->masterKeyLength;
    }

    public function setMasterKeyLength(int $masterKeyLength): self
    {
        $this->masterKeyLength = $masterKeyLength;

        return $this;
    }

    public function getKeySalt(): ?string
    {
        return $this->keySalt;
    }

    public function setKeySalt(string $keySalt): self
    {
        $this->keySalt = $keySalt;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}
