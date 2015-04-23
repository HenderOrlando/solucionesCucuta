<?php

namespace SolucionesCucuta\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etiqueta
 *
 * @ORM\Table(name="etiqueta", uniqueConstraints={@ORM\UniqueConstraint(name="nombre", columns={"nombre"})})
 * @ORM\Entity(repositoryClass="SolucionesCucuta\AppBundle\Entity\Repository\EtiquetaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Etiqueta
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
     * @ORM\Column(name="icon", type="string", length=100, nullable=true)
     */
    private $icon;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="Etiqueta", mappedBy="padre")
     */
    private $hijos;

    /**
     * @ORM\ManyToOne(targetEntity="Etiqueta", inversedBy="hijos")
     * @ORM\JoinColumn(name="padre_id", referencedColumnName="id")
     */
    private $padre;

    /**
     * @ORM\ManyToOne(targetEntity="Tipo", inversedBy="etiquetas")
     */
    private $tipo;

    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="etiquetas")
     */
    private $estado;

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
     * @ORM\ManyToMany(targetEntity="Archivo", mappedBy="etiquetas")
     */
    private $archivos;

    /**
     * @ORM\ManyToMany(targetEntity="Publicacion", mappedBy="etiquetas")
     */
    private $publicaciones;

    /*** =========================================================================== ***/

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdat = $this->updatedat = new \DateTime('now');
        $this->hijos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Etiqueta
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
     * @return Etiqueta
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
     * Set icon
     *
     * @param string $icon
     * @return Etiqueta
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Etiqueta
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
     * @return Etiqueta
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
     * @return Etiqueta
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
     * Add hijo
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Etiqueta $hijo
     * @return Etiqueta
     */
    public function addHijo(\SolucionesCucuta\AppBundle\Entity\Etiqueta $hijo)
    {
        $this->hijo[] = $hijo;

        return $this;
    }

    /**
     * Remove hijo
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Etiqueta $hijo
     */
    public function removeHijo(\SolucionesCucuta\AppBundle\Entity\Etiqueta $hijo)
    {
        $this->hijo->removeElement($hijo);
    }

    /**
     * Get hijo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHijo()
    {
        return $this->hijo;
    }

    /**
     * Set padre
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Etiqueta $padre
     * @return Etiqueta
     */
    public function setPadre(\SolucionesCucuta\AppBundle\Entity\Etiqueta $padre = null)
    {
        $this->padre = $padre;

        return $this;
    }

    /**
     * Get padre
     *
     * @return \SolucionesCucuta\AppBundle\Entity\Etiqueta 
     */
    public function getPadre()
    {
        return $this->padre;
    }

    /**
     * Set tipo
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Tipo $tipo
     * @return Etiqueta
     */
    public function setTipo(\SolucionesCucuta\AppBundle\Entity\Tipo $tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \SolucionesCucuta\AppBundle\Entity\Tipo 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set estado
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Estado $estado
     * @return Etiqueta
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
     * Get hijos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHijos()
    {
        return $this->hijos;
    }

    /**
     * Add archivos
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Archivo $archivos
     * @return Etiqueta
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
     * Add publicaciones
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Publicacion $publicaciones
     * @return Etiqueta
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
