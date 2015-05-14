<?php

namespace SolucionesCucuta\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Archivo
 *
 * @ORM\Table(name="archivo", uniqueConstraints={@ORM\UniqueConstraint(name="nombre", columns={"nombre"})})
 * @ORM\Entity(repositoryClass="SolucionesCucuta\AppBundle\Entity\Repository\ArchivoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Archivo
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
     * @ORM\Column(name="hash", type="string", length=255, nullable=true)
     */
    private $hash;

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
     * @var string
     *
     * @ORM\Column(name="link", type="text", nullable=true)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var number
     *
     * @ORM\Column(name="num_clicks", type="bigint", nullable=false, options={"unsigned"=true, "default" = 0})
     */
    private $clicks;

    /**
     * @var number
     *
     * @ORM\Column(name="num_print", type="bigint", nullable=false, options={"unsigned"=true, "default" = 0})
     */
    private $prints;

    /**
     * @ORM\ManyToOne(targetEntity="Tipo", inversedBy="archivos")
     */
    private $tipo;

    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="archivos")
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="archivos")
     */
    private $usuario;

    /**
     * @ORM\ManyToMany(targetEntity="Etiqueta", inversedBy="archivos")
     * @ORM\JoinTable(name="etiquetas_archivos")
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

    /**
     * @ORM\ManyToOne(targetEntity="Publicacion", inversedBy="archivos")
     */
    private $publicacion;

    /**
     * @Assert\File(maxSize="10000000")
     */
    private $file;

    private $temp;

    /*** =========================================================================== ***/

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clicks = 0;
        $this->prints = 0;
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
     * Set nombre
     *
     * @param string $nombre
     * @return Archivo
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
     * @return Archivo
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Archivo
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
     * Set url
     *
     * @param string $url
     * @return Archivo
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Archivo
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * set clicks
     *
     * @param integer $clicks
     * @return Archivo
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;

        return $this;
    }

    /**
     * Add click
     *
     * @return Archivo
     */
    public function addClick()
    {
        $this->clicks++;

        return $this;
    }

    /**
     * Get clicks
     *
     * @return integer
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * set prints
     *
     * @param integer $prints
     * @return Archivo
     */
    public function setPrints($prints)
    {
        $this->prints = $prints;

        return $this;
    }

    /**
     * Add print
     *
     * @return Archivo
     */
    public function addPrint()
    {
        $this->prints++;

        return $this;
    }

    /**
     * Get prints
     *
     * @return integer
     */
    public function getPrints()
    {
        return $this->prints;
    }

    /**
     * Set createdat
     *
     * @param \DateTime $createdat
     * @return Archivo
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
     * @return Archivo
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
     * @return Archivo
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
     * @return Archivo
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
     * @return Archivo
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

    public function getAbsolutePath()
    {
        return null === $this->url
            ? null
            : $this->getUploadRootDir().'/'.$this->url;
    }

    public function getWebPath()
    {
        return null === $this->url
            ? null
            : $this->getUploadDir().'/'.$this->url;
    }

    protected function getUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // se deshace del __DIR__ para no meter la pata
        // al mostrar el documento/imagen cargada en la vista.
        return 'uploads/archivos';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->url)) {
            // store the old name to delete after the update
            $this->temp = $this->url;
            $this->url = null;
        } else {
            $this->url = sha1(uniqid(mt_rand(), true));
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if ($this->getFile()) {
            // haz lo que quieras para generar un nombre único
            $filename = sha1(uniqid(mt_rand(), true));
            $this->url = $filename.'.'.$this->getFile()->guessExtension();
        }
        $this->setHash();
    }
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // si hay un error al mover el archivo, move() automáticamente
        // envía una excepción. This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->url);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Set usuario
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Usuario $usuario
     * @return Archivo
     */
    public function setUsuario(\SolucionesCucuta\AppBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \SolucionesCucuta\AppBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    /**
     * Set publicacion
     *
     * @param \SolucionesCucuta\AppBundle\Entity\Publicacion $publicacion
     * @return Archivo
     */
    public function setPublicacion(\SolucionesCucuta\AppBundle\Entity\Publicacion $publicacion = null)
    {
        $this->publicacion = $publicacion;

        return $this;
    }

    /**
     * Get publicacion
     *
     * @return \SolucionesCucuta\AppBundle\Entity\Publicacion
     */
    public function getPublicacion()
    {
        return $this->publicacion;
    }

    public function getHash(){
        if(!$this->hash){
            $this->setHash();
        }
        return $this->hash;
    }

    public function setHash(){
        $this->hash = sha1($this->getId().'-'.$this->getSlug());

        return $this;
    }
}
