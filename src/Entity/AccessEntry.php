<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccessEntryRepository")
 */
class AccessEntry
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userName;


    /**
     * @ORM\Column(type="string", length=127)
     * @var string
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=127)
     * @var string
     * ciphr method and mode selection
     */
    protected $cipherMethod;

    /**
     * @ORM\Column(type="string", length=127, options={"default" = "sha512"})
     * @var string
     * Name of selected hashing algorithm (e.g. "md5", "sha256", "haval160,4", etc..)
     */
    protected $hashAlgorithm;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $masterKeyLength;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $encryptedPassword;

    private $decryptedPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $encryptedKey;

    private $decryptedKey;

    /**
     * @ORM\ManyToOne(targetEntity="AccessEntryCategory")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $category;


    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $modifiedBy;

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

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false, options={"default" = 1})
     */
    private $cipherVersion=1;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getEncryptedPassword(): ?string
    {
        return $this->encryptedPassword;
    }

    public function setEncryptedPassword(string $encryptedPassword): self
    {
        $this->encryptedPassword = $encryptedPassword;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getEncryptedKey(): ?string
    {
        return $this->encryptedKey;
    }

    public function setEncryptedKey(string $encryptedKey): self
    {
        $this->encryptedKey = $encryptedKey;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function __construct()
    {
        $this->cipherMethod = 'aes-256-cbc';
        $this->hashAlgorithm = 'sha512';
        $this->cipherVersion = 1;
        $this->masterKeyLength = 512;
        $this->salt = bin2hex(openssl_random_pseudo_bytes(16));
    }


    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
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

    public function getCategory(): ?AccessEntryCategory
    {
        return $this->category;
    }

    public function setCategory(?AccessEntryCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getModifiedBy(): ?User
    {
        return $this->modifiedBy;
    }

    public function setModifiedBy(?User $modifiedBy): self
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    public function getDecryptedPassword(): ?string
    {
        return $this->decryptedPassword;
    }

    public function setDecryptedPassword(?string $decryptedPassword): self
    {
        $this->decryptedPassword = $decryptedPassword;

        return $this;
    }

    public function getDecryptedKey(): ?string
    {
        return $this->decryptedKey;
    }

    public function setDecryptedKey(?string $decryptedKey): self
    {
        $this->decryptedKey = $decryptedKey;

        return $this;
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
    public function decrypt($what, string $key = null)
    {
        $iv_size = openssl_cipher_iv_length($this->getCipherMethod());

        $ciphertext_dec = hex2bin($what);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);
        return openssl_decrypt($ciphertext_dec, $this->getCipherMethod(), $key, OPENSSL_RAW_DATA, $iv_dec);
    }


    public function encryptClassifiedFields($key) {
        $this->setEncryptedPassword( $this->encrypt( $this->getDecryptedPassword(), hash($this->getHashAlgorithm(), $this->getSalt().$key) ) );
        $this->setEncryptedKey( $this->encrypt( $this->getDecryptedKey(), hash($this->getHashAlgorithm(), $this->getSalt().$key) ) );
    }

    public function decryptClassifiedFields($key) {
        $this->setDecryptedPassword( $this->decrypt( $this->getEncryptedPassword(), hash($this->getHashAlgorithm(), $this->getSalt().$key) ) );
        $this->setDecryptedKey( $this->decrypt( $this->getEncryptedKey(), hash($this->getHashAlgorithm(), $this->getSalt().$key) ) );

    }

    public function getHashAlgorithm(): ?string
    {
        return $this->hashAlgorithm;
    }

    public function setHashAlgorithm(string $hashAlgorithm): self
    {
        $this->hashAlgorithm = $hashAlgorithm;

        return $this;
    }

    public function getCipherVersion(): ?int
    {
        return $this->cipherVersion;
    }

    public function setCipherVersion(int $cipherVersion): self
    {
        $this->cipherVersion = $cipherVersion;

        return $this;
    }
}
