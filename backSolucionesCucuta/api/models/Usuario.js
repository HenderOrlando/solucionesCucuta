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
    username: {
      type: 'string',
      unique: true,
      required: true
    },
    descripcion:{
      type: 'string',
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
    createAt: {
      type: 'datetime',
      defaultsTo: function (){ return new Date(); }
    },
    updateAt: {
      type: 'datetime',
      defaultsTo: function (){ return new Date(); }
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
    toJSON: function(){
      var obj = this.toObject();
      delete obj.password;
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

