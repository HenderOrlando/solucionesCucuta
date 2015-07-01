/**
* Archivo.js
*
* @description :: TODO: You might write a short summary of how this model works and what it represents here.
* @docs        :: http://sailsjs.org/#!documentation/models
*/

module.exports = {
  autoPk: true,
  autoUpdateAt: true,
  autoCreateAt: true,
  attributes: {
    id: {
      type: 'string',
      primaryKey: true,
      unique: true
    },
    slug: {
      type: 'string',
      unique: true
    },
    nombre: {
      type: 'string',
      unique: true,
      required: true
    },
    descripcion:{
      type: 'string',
      required: true
    },
    createAt: {
      type: 'datetime',
      defaultsTo: function (){ return new Date(); }
    },
    updateAt: {
      type: 'datetime',
      defaultsTo: function (){ return new Date(); }
    },
    rol: {
      model: 'rol'
    },
    url: {
      type: 'string',
      required: true
    },
    rol: {
      model: 'rol'
    },
    tipo: {
      model: 'tipo'
    },
    estado: {
      model: 'estado'
    },
    etiquetas: {
      collection: 'etiqueta',
      via: 'archivos'
    },
    usuarios: {
      collection: 'usuario',
      via: 'archivos'
    }
  }
};

