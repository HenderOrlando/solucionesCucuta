<?php

namespace SolucionesCucuta\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estado
 *
 * @ORM\Table(name="estado", uniqueConstraints={@ORM\UniqueConstraint(name="nombre", columns={"nombre"})})
 * @ORM\Entity(repositoryClass="SolucionesCucuta\AppBundle\Entity\Repository\EstadoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Estado
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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedat;

    /**
     * @ORM\OneToMany(targetEntity="Archivo", mappedBy="estado")
     */
    private $archivos;

    /**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="estado")
     */
    private $usuarios;

    /**
     * @ORM\OneToMany(targetEntity="Publicacion", mappedBy="estado")
     */
    private $publicaciones;

    /**
     * @ORM\OneToMany(targetEntity="Etiqueta", mappedBy="estado")
     */
    private $etiquetas;

    /*** =========================================================================== ***/

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdat = $this->updatedat = new \DateTime('now');
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->publicaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etiquetas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Estado
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
     * Set slug
     *
     * @param string $slug
     * @return Estado
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Estado
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
     * Set createdat
     *
     * @param \DateTime $createdat
     * @return Estado
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
     * @return Estado
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
     * Add usuarios
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Usuario $usuarios
     * @return Estado
     */
    public function addUsuario(\SolucionesCucuta\AppBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios[] = $usuarios;

        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Usuario $usuarios
     */
    public function removeUsuario(\SolucionesCucuta\AppBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios->removeElement($usuarios);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Add publicaciones
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Publicacion $publicaciones
     * @return Estado
     */
    public function addPublicacione(\SolucionesCucuta\AppBundle\Entity\Publicacion $publicaciones)
    {
        $this->publicaciones[] = $publicaciones;

        return $this;
    }

    /**
     * Remove publicaciones
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Publicacion $publicaciones
     */
    public function removePublicacione(\SolucionesCucuta\AppBundle\Entity\Publicacion $publicaciones)
    {
        $this->publicaciones->removeElement($publicaciones);
    }

    /**
     * Get publicaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPublicaciones()
    {
        return $this->publicaciones;
    }

    /**
     * Add etiquetas
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Etiqueta $etiquetas
     * @return Estado
     */
    public function addEitqueta(\SolucionesCucuta\AppBundle\Entity\Etiqueta $etiquetas)
    {
        $this->etiquetas[] = $etiquetas;

        return $this;
    }

    /**
     * Remove etiquetas
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Etiqueta $etiquetas
     */
    public function removeEitqueta(\SolucionesCucuta\AppBundle\Entity\Etiqueta $etiquetas)
    {
        $this->etiquetas->removeElement($etiquetas);
    }

    /**
     * Get etiquetas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEitquetas()
    {
        return $this->etiquetas;
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
        return $this->getNombre();
    }
}
