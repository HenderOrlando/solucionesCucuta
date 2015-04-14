/**
 * ArchivoController
 *
 * @description :: Server-side logic for managing archivoes
 * @help        :: See http://links.sailsjs.org/docs/controllers
 */

module.exports = {

  /**
   * `ArchivoController.count()`
   */
  count: function (req, res, next) {
    count = 0;
    Archivo.count().exec(function(err, found){
      if(err){
        next(err);
      }
      if(found){
        count = found
      }
      res.json({
        count: count
      });
    });
  },

  /**
   * `ArchivoController.upload()`
   */
  upload: function (req, res, next) {
    req.file('archivo').upload(
      { dirname: './assets/archivos' },
      function (err, uploadedFiles) {
        if (err){
          return res.negotiate(err);
        }
        return res.json({
            msgs: [
              {
                type: 'succes',
                title: 'Subida Completa',
                content: 'Se guard'+(uploadedFiles.length == 1?'ó':'aron ')+' Archivos'
              }
            ]
        });
      }
    );
  },

  /**
   * `ArchivoController.download()`
   */
  download: function (req, res, next) {
    var id = req.param('id');
    Archivo.findOne(id).exec(function(err, archivo){
      if(err){
        return res.negotiate(err);
      }
      if(!archivo){
        res.status(404);
        res.send({
          msgs: [
            {
              type: 'error',
              title: 'clave inválida',
              content: 'No se valido la clave, intenta mas tarde'
            }
          ]
        });
      }else{
        var skipperDisk = require('skipper-disk');
        var fileAdapter = skipperDisk();

        fileAdapter.read(archivo.url)
          .on('error', function(err){
            return res.serverError(err);
          })
          .on('finish', function(){

          })
          .pipe(res);
      }
    });
  }

};

