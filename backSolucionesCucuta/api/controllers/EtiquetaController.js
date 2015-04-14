/**
 * EtiquetaController
 *
 * @description :: Server-side logic for managing etiquetas
 * @help        :: See http://links.sailsjs.org/docs/controllers
 */

module.exports = {

  /**
   * `MainController.count()`
   */
  count: function (req, res, next) {
    count = 0;
    Etiqueta.count().exec(function(err, found){
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
  }

};

