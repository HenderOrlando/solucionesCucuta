<?php

namespace SolucionesCucuta\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Publicacion
 *
 * @ORM\Table(name="publicacion", uniqueConstraints={@ORM\UniqueConstraint(name="titulo", columns={"titulo"})})
 * @ORM\Entity(repositoryClass="SolucionesCucuta\AppBundle\Entity\Repository\PublicacionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Publicacion
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
     * @ORM\Column(name="titulo", type="string", length=255, nullable=true)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitulo", type="string", length=255, nullable=true)
     */
    private $subtitulo;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="text", nullable=true)
     */
    private $contenido;

    /**
     * @ORM\OneToMany(targetEntity="Archivo", mappedBy="publicacion")
     */
    private $archivos;

    /**
     * @ORM\ManyToOne(targetEntity="Tipo", inversedBy="publicaciones")
     */
    private $tipo;

    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="publicaciones")
     */
    private $estado;

    /**
     * @ORM\ManyToMany(targetEntity="Etiqueta", inversedBy="publicaciones")
     * @ORM\JoinTable(name="etiquetas_publicaciones")
     */
    private $etiquetas;

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

    /*** =========================================================================== ***/

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdat = $this->updatedat = new \DateTime('now');
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
     * Set titulo
     *
     * @param string $titulo
     * @return Publicacion
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
        $this->slug = $this->slugify($titulo);

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set subtitulo
     *
     * @param string $subtitulo
     * @return Publicacion
     */
    public function setSubtitulo($subtitulo)
    {
        $this->subtitulo = $subtitulo;

        return $this;
    }

    /**
     * Get subtitulo
     *
     * @return string 
     */
    public function getSubtitulo()
    {
        return $this->subtitulo;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Publicacion
     */
    public function setSlug($slug)
    {
        if(!$slug){
            $slug = $this->getTitulo();
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
     * Set contenido
     *
     * @param string $contenido
     * @return Publicacion
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string 
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set createdat
     *
     * @param \DateTime $createdat
     * @return Publicacion
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
     * @return Publicacion
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
     * Set tipo
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Tipo $tipo
     * @return Publicacion
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
     * @return Publicacion
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
     * Add etiquetas
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Etiqueta $etiquetas
     * @return Publicacion
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
     * Add archivos
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Archivo $archivos
     * @return Publicacion
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
        return $this->getTitulo();
    }
}
