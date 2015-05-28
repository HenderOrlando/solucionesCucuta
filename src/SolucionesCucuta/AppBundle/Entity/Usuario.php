<?php

namespace SolucionesCucuta\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", uniqueConstraints={@ORM\UniqueConstraint(name="username", columns={"username"})})
 * @ORM\Entity(repositoryClass="SolucionesCucuta\AppBundle\Entity\Repository\UsuarioRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Usuario implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="usuarios")
     */
    private $estado;

    /**
     * @ORM\OneToMany(targetEntity="Archivo", mappedBy="usuario")
     */
    private $archivos;

    /**
     * @ORM\ManyToOne(targetEntity="Rol", inversedBy="usuarios")
     */
    private $rol;

    /**
     * @ORM\ManyToMany(targetEntity="Etiqueta", inversedBy="usuarios")
     */
    private $etiquetas;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdat;

    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedat;

    private $roles;

    /*** =========================================================================== ***/

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isActive = false;
        $this->roles = array();
        $this->archivos = array();
        $this->createdat = $this->updatedat = new \DateTime('now');
        $this->salt = sha1($this->createdat->format('y-m-d H:i:s'));
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Usuario
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        $this->slug = $this->slugify($nombre);

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Usuario
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Usuario
     */
    public function setSlug($slug)
    {
        if(!$slug){
            $slug = $this->getNombre();
        }
        $this->slug = $this->slugify($slug);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set createdat
     *
     * @param \DateTime $createdat
     * @return Usuario
     */
    public function setCreatedat($createdat)
    {
        $this->createdat = $createdat;

        return $this;
    }

    /**
     * Get createdat
     *
     * @return \DateTime 
     */
    public function getCreatedat()
    {
        return $this->createdat;
    }

    /**
     * Set updatedat
     *
     * @param \DateTime $updatedat
     * @return Usuario
     */
    public function setUpdatedat($updatedat)
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    /**
     * Get updatedat
     *
     * @return \DateTime 
     */
    public function getUpdatedat()
    {
        return $this->updatedat;
    }

    /**
     * Set estado
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Estado $estado
     * @return Usuario
     */
    public function setEstado(\SolucionesCucuta\AppBundle\Entity\Estado $estado = null)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \SolucionesCucuta\AppBundle\Entity\Estado 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set rol
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Rol $rol
     * @return Usuario
     */
    public function setRol(\SolucionesCucuta\AppBundle\Entity\Rol $rol = null)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \SolucionesCucuta\AppBundle\Entity\Rol 
     */
    public function getRol()
    {
        return $this->rol;
    }

    public function getRoles(){
        if(!$this->roles || empty($this->roles)){
            /*$roles = array();
            foreach($this->getRoles() as $rol){
                $roles = array_merge($roles, array($rol->getSlug()));
            }
            $this->roles = $roles;*/
            $this->roles = array(
                'ROLE_'.strtoupper($this->getRol()->getSlug())
            );
        }
        return $this->roles;
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        $this->isActive = $this->getEstado()->getSlug() === 'activo';
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
            // see section on salt below
            // $this->salt,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
        if($this->isActive){
            // Cambiar el estado a activo
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdateAt()
    {
        $this->updatedat = new \DateTime();
    }

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

    public function __toString(){
        return $this->getNombre()?$this->getNombre():$this->getEmail();
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
        $this->isActive = $this->getEstado()->getSlug() === 'activo';
        return $this->isActive;
    }



    /**
     * Add archivos
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Archivo $archivos
     * @return Estado
     */
    public function addArchivo(\SolucionesCucuta\AppBundle\Entity\Archivo $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Archivo $archivos
     */
    public function removeArchivo(\SolucionesCucuta\AppBundle\Entity\Archivo $archivos)
    {
        $this->archivos->removeElement($archivos);
    }

    /**
     * Get archivos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArchivos()
    {
        return $this->archivos;
    }

    /**
     * Add etiquetas
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Etiqueta $etiquetas
     * @return Estado
     */
    public function addEtiqueta(\SolucionesCucuta\AppBundle\Entity\Etiqueta $etiquetas)
    {
        $this->etiquetas[] = $etiquetas;

        return $this;
    }

    /**
     * Remove etiquetas
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Etiqueta $etiquetas
     */
    public function removeEtiqueta(\SolucionesCucuta\AppBundle\Entity\Etiqueta $etiquetas)
    {
        $this->etiquetas->removeElement($etiquetas);
    }

    /**
     * Get etiquetas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtiquetas()
    {
        return $this->etiquetas;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preSlug()
    {
        if (!$this->getSlug()) {
            $this->slug = $this->slugify($this->getNombre());
        }else{
            $this->slug = $this->slugify($this->getSlug());
        }
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBannersLaterales($all = true){
        $bannersLaterales = $this->getArchivos()->filter(
            function(Archivo $archivo) use ($all){
                if(!$all){
                    $archivo->getEtiquetas()->exists(function($key, $etiqueta){
                        return $etiqueta->getSlug() === 'principal';
                    });
                }
                return $archivo->getTipo()->getSlug() == 'banner-lateral';
            }
        );
        if($bannersLaterales){
            return $bannersLaterales;
        }
        return null;
    }

    /**
     * @return \SolucionesCucuta\AppBundle\Entity\Archivo
     */
    public function getBannerLateral($plural = false){
        $bannersLaterales = $this->getBannersLaterales(false);
        if($bannersLaterales){
            return $bannersLaterales->first();
        }
        return null;
    }

    public function getSrcBannerLateral(){
        $archivo = $this->getBannerLateral();
        if(is_object($archivo) && method_exists($archivo,'getWebPath')){
            return $archivo->getWebPath();
        }
        return '#';
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBannersSuperiores($all = true){
        $bannersSuperiores = $this->getArchivos()->filter(
            function(Archivo $archivo) use ($all){
                if(!$all){
                    $archivo->getEtiquetas()->exists(function($key, $etiqueta){
                        return $etiqueta->getSlug() === 'principal';
                    });
                }
                return $archivo->getTipo()->getSlug() == 'banner-superior';
            }
        );
        if($bannersSuperiores){
            return $bannersSuperiores;
        }
        return null;
    }

    /**
     * @return \SolucionesCucuta\AppBundle\Entity\Archivo
     */
    public function getBannerSuperior($plural = false){
        $bannersSuperiores = $this->getBannersSuperiores(false);
        if($bannersSuperiores){
            return $bannersSuperiores->first();
        }
        return null;
    }

    public function getSrcBannerSuperior(){
        $archivo = $this->getBannerSuperior();
        if(is_object($archivo) && method_exists($archivo,'getWebPath')){
            return $archivo->getWebPath();
        }
        return '#';
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRedesSociales($all = true){
        $bannersSuperiores = $this->getArchivos()->filter(
            function(Archivo $archivo) use ($all){
                if(!$all){
                    $archivo->getEtiquetas()->exists(function($key, $etiqueta){
                        return $etiqueta->getSlug() === 'principal';
                    });
                }
                return $archivo->getTipo()->getSlug() == 'red-social';
            }
        );
        if($bannersSuperiores){
            return $bannersSuperiores;
        }
        return null;
    }

    /**
     * @return \SolucionesCucuta\AppBundle\Entity\Archivo
     */
    public function getRedSocial(){
        $bannersSuperiores = $this->getRedesSociales(false);
        if($bannersSuperiores){
            return $bannersSuperiores->first();
        }
        return null;
    }

    public function getSrcRedSocial(){
        $archivo = $this->getRedSocial();
        if(is_object($archivo) && method_exists($archivo,'getWebPath')){
            return $archivo->getWebPath();
        }
        return '#';
    }
}
