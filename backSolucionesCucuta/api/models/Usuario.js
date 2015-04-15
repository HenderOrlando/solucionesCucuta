/**
* Usuario.js
*
* @description :: TODO: You might write a short summary of how this model works and what it represents here.
* @docs        :: http://sailsjs.org/#!documentation/models
*/
var bcrypt = require('bcrypt');

module.exports = {
  autoUpdatAt: true,
  autoCreateAt: true,
  attributes: {
    email: {
      type: 'email',
      unique: true
    },
    nombre: {
      type: 'string'
    },
    username: {
      type: 'string',
      unique: true,
      required: true
    },
    descripcion:{
      type: 'text',
      required: true
    },
    slug: {
      type: 'string'
    },
    password:{
      type: 'string',
      required: true
    },
    salt:{
      type: 'string'
    },
    publicaciones: {
      collection: 'Publicacion',
      via: 'usuarios',
      dominant: true
    },
    archivos: {
      collection: 'Archivo',
      via: 'usuarios',
      dominant: true
    },
    estado: {
      model: 'Estado'
    },
    rol: {
      model: 'Rol'
    },
    etiquetas: {
      collection: 'Etiqueta',
      via: 'usuarios'
    },
    // Functions
    hasRol: function(roles){
      return this.rol.slug.toLowerCase() == roles.slug.toLowerCase();
      /*Usuario.find(this).populate('roles',roles).exec(function(err, usuario){
       if(usuarios[0].roles.length){
       return true;
       }
       });*/
      return false;
    },
    hasEtiqueta: function(etiquetas){
      Usuario.find(this).populate('etiquetas',etiquetas).exec(function(err, usuario){
        if(usuarios[0].etiquetas.length){
          return true;
        }
      });
      return false;
    },
    hasArchivo: function(archivos){
      Usuario.find(this).populate('archivos',archivos).exec(function(err, usuario){
        if(usuarios[0].archivos.length){
          return true;
        }
      });
      return false;
    },
    hasPublicaciones: function(publicaciones){
      Usuario.find(this).populate('publicaciones',publicaciones).exec(function(err, usuarios){
        if(usuarios[0].publicaciones.length){
          return true;
        }
      });
      return false;
    },
    toJSON: function(){
      var obj = this.toObject();
      delete obj.password;
      delete obj.salt;
      return obj;
    }
  },

  // Lifecycle Callbacks
  beforeCreate: function (values, next) {
    if(!values.username){
      return next({err: ["Debe existir un nombre de usuario!"]});
    }
    values.slug = this.capitalizeSlug(values.username);

    bcrypt.genSalt(10, function(err, salt) {
      values.salt = salt;
      bcrypt.hash(values.password, values.salt, function(err, hash) {
        if(err) {
          next(err);
        } else {
          values.password = hash;
          next(null, values);
        }
      });
    });
  }
};

